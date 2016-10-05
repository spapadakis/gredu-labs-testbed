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
use GrEduLabs\Schools\Service\LabServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\InputFilter\InputFilterInterface;

class ApplicationForm {

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
     * @var LabServiceInterface
     */
    protected $labService;

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

    /**
     *
     * @var AuthenticationServiceInterface
     */
    protected $authService;
    
    /**
     *
     * @var int The version of the application form to handle
     */
    protected $version;
    
    /**
     *
     * @var type SLIM application container 
     */
    protected $container;

    /**
     *
     * @var string
     */
    protected $successUrl;

    public function __construct(
        Twig $view,
        AssetServiceInterface $assetsService,
        LabServiceInterface $labService,
        ApplicationFormServiceInterface $appFormService,
        InputFilterInterface $appFormInputFilter,
        AuthenticationServiceInterface $authService,
        $successUrl,
        $version,
        $container
    ) {
        $this->view = $view;
        $this->assetsService = $assetsService;
        $this->labService = $labService;
        $this->appFormService = $appFormService;
        $this->appFormInputFilter = $appFormInputFilter;
        $this->authService        = $authService;
        $this->successUrl         = $successUrl;
        $this->version            = $version;
        $this->container = $container;
    }

    public function __invoke(Request $req, Response $res) {
        $school = $req->getAttribute('school');

        if ($req->isPost()) {
            $reqParams = $req->getParams();
            $items = $reqParams['items'];
            $itemsNew = array();
            $itemsLength = count($items);
            $j = 0;
            foreach ($items as $item) {
                $itemsNew[$j] = $item;
                $j++;
            }
            $reqParams['items'] = $itemsNew;
            var_export($reqParams);
            $this->appFormInputFilter->setData(array_merge($reqParams, [
                'school_id' => $school->id,
                'submitted_by' => $this->authService->getIdentity()->mail,
            ]));
            $isValid = $this->appFormInputFilter->isValid();
            if ($isValid) {
                $data = $this->appFormInputFilter->getValues();
                $appForm = $this->appFormService->submit($data);
                $_SESSION['applicationForm']['appForm'] = $appForm;
                $res = $res->withRedirect($this->successUrl);

                return $res;
            }

            $this->view['form'] = [
                'is_valid' => $isValid,
                'values' => $this->appFormInputFilter->getValues(),
                'raw_values' => $this->appFormInputFilter->getRawValues(),
                'messages' => $this->appFormInputFilter->getMessages(),
            ];
        }

        $loadForm = (bool) $req->getParam('load', false);
        $this->view['choose'] = !$loadForm && !$req->isPost();
        if (!$req->isPost() && $loadForm) {
            // take care of new options in applications and migrate existing ones
            if (null !== ($appForm = $this->appFormService->findSchoolApplicationForm($school->id))) {
//                $this->view['form'] = [
//                    'values' => $appForm,
//                ];
                $this->container['logger']->info("Checking for migration");
                /**
                 * Do mapping of old items to new only if items do exit (old form) 
                 * and the map is available at the app settings 
                 * TODO CHECK VERSIONS!!!! 
                 */
                if (isset($appForm['items']) &&
                        isset($this->container['settings']['application_form']['itemcategory']['map']['items'])) {
                    $items_map = $this->container['settings']['application_form']['itemcategory']['map']['items'];
                    $appForm['items'] = array_map(function ($item) use ($items_map) {
                        $migrate_values = [];
                        $this->container['logger']->info(var_export($item, true));
 
//                        $this->container['logger']->info(var_export($items_map[$item['itemcategory_id']], true));
                        if (isset($items_map[$item['itemcategory_id']]) &&
                                intval($items_map[$item['itemcategory_id']]) > 0) {
                            $migrate_values = [
                                'itemcategory_prev' => $item['itemcategory_id'],
                                'itemcategory_id_prev' => $item['itemcategory_id'],
                                'itemcategory_id' => intval($items_map[$item['itemcategory_id']]),
                            ];
                        } else {
                            $migrate_values = [
                                'itemcategory_prev' => '',
                                'itemcategory_id_prev' => -1,
                            ];
                        }
                        $migrate_values['prev_form_load'] = true;
                        return array_merge($item, $migrate_values);
                    }, $appForm['items']);
                }
                $this->view['form'] = [
                    'values' => $appForm,
                ];
            }
        }
        $labs = $this->labService->getLabsBySchoolId($school->id);
        $res = $this->view->render($res, 'application_form/form.twig', [
            'lab_choices' => array_map(function ($lab) {
                return ['value' => $lab['id'], 'label' => $lab['name']];
            }, $labs),
            'type_choices' => array_map(function ($category) {
                return ['value' => $category['id'], 'label' => $category['name']];
            }, $this->assetsService->getAllItemCategories($this->version)),
        ]);

        return $res;
    }
}
