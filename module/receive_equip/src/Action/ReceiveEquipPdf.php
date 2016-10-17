<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ReceiveEquip\Action;

use GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ReceiveEquipPdf
{
    /**
     *
     * @var ReceiveEquipServiceInterface
     */
    protected $receiveEquipService;

    /**
     *
     * @var Twig
     */
    protected $view;

    public function __construct(ReceiveEquipServiceInterface $receiveEquipService, Twig $view)
    {
        $this->receiveEquipService = $receiveEquipService;
        $this->view           = $view;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school  = $req->getAttribute('school');
        $receiveEquip = $this->receiveEquipService->findSchoolReceiveEquip($school->id);
        if (null === $receiveEquip) {
            return $res->withStatus(404);
        }

        $html = $this->view->fetch('receive_equip/pdf.twig', [
            'school'  => $school,
            'receiveEquip' => $receiveEquip,
            'logo'    => base64_encode(file_get_contents(__DIR__ . '/../../public/img/receive_equip/minedu_logo.jpg')),
            'style'   => file_get_contents(__DIR__ . '/../../public/css/receive_equip/pdf.css'),
        ]);
        $pdf = new \Dompdf\Dompdf([
            'default_paper_size'   => 'A4',
            'default_font'         => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'is_remote_enabled'    => false,
        ]);
        $pdf->loadHtml($html);
        $pdf->render();
        $filename = 'edulabs_receive_equip_' . $receiveEquip['id'] . '.pdf';
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
