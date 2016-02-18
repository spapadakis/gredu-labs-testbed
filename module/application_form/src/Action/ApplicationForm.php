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
use GrEduLabs\Schools\Service\AssetServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Zend\InputFilter\InputFilterInterface;

class ApplicationForm
{
    /**
     * @var Twig
     */
    protected $view;

    /**
     *
     * @var AssetServiceInterface
     */
    protected $assetsService;

    /**
     *
     * @var ApplicationFormServiceInterface
     */
    protected $appFormService;

    /**
     *
     * @var InputFilterInterface
     */
    protected $appFormInputFilter;


    public function __construct(
        Twig $view,
        AssetServiceInterface $assetsService,
        ApplicationFormServiceInterface $appFormService,
        InputFilterInterface $appFormInputFilter
    ) {
        $this->view               = $view;
        $this->assetsService      = $assetsService;
        $this->appFormService     = $appFormService;
        $this->appFormInputFilter = $appFormInputFilter;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school = $req->getAttribute('school');
        if ($req->isPost()) {
            $this->appFormInputFilter->setData(array_merge($req->getParams(), [
                'school_id' => $school->id,
            ]));
            if ($this->appFormInputFilter->isValid()) {
                $data = $this->appFormInputFilter->getValues();
                $this->appFormService->submit($data);
            }
            var_dump($this->appFormInputFilter->getMessages());
            die('error');
            $this->view['messages'] = $this->appFormInputFilter->getMessages();
        }

        $res = $this->view->render($res, 'application_form/form.twig', [
            'type_choices' => array_map(function ($category) {
                return ['value' => $category['id'], 'label' => $category['name']];
            }, $this->assetsService->getAllItemCategories()),
            'apply_for_choices' => array_map(function ($choice) {
                return ['value' => $choice, 'label' => $choice];
            }, $this->appFormService->getApplyForChoices()),

        ]);

        return $res;
    }
}
