<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ApplicationForm\Action;

use GrEduLabs\ApplicationForm\Service\ApplicationFormServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ApplicationFormPdf
{
    /**
     *
     * @var ApplicationFormServiceInterface
     */
    protected $appFormService;

    /**
     *
     * @var Twig
     */
    protected $view;

    public function __construct(ApplicationFormServiceInterface $appFormService, Twig $view)
    {
        $this->appFormService = $appFormService;
        $this->view           = $view;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school  = $req->getAttribute('school');
        $appForm = $this->appFormService->findSchoolApplicationForm($school->id);
        if (null === $appForm) {
            return $res->withStatus(404);
        }

        $html = $this->view->fetch('application_form/pdf.twig', [
            'school'  => $school,
            'appForm' => $appForm,
            'logo'    => base64_encode(file_get_contents(__DIR__ . '/../../public/img/application_form/minedu_logo.jpg')),
            'style'   => file_get_contents(__DIR__ . '/../../public/css/application_form/pdf.css'),
        ]);
        $pdf = new \Dompdf\Dompdf([
            'default_paper_size'   => 'A4',
            'default_font'         => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'is_remote_enabled'    => false,
        ]);
        $pdf->loadHtml($html);
        $pdf->render();
        $filename = 'edulabs_app_form_' . $appForm['id'] . '.pdf';
        $str      = $pdf->output();
        $length   = mb_strlen($str, '8bit');

        return $res->withHeader('Cache-Control', 'private')
            ->withHeader('Content-type', 'application/pdf')
            ->withHeader('Content-Length', $length)
            ->withHeader('Content-Disposition', 'attachment;  filename=' . $filename)
            ->withHeader('Accept-Ranges', $length)
            ->write($str);
    }
}
