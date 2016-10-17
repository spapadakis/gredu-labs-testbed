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
use GrEduLabs\Schools\Service\AssetServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\InputFilter\InputFilterInterface;

class ReceiveEquip {

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
     * @var ReceiveEquipServiceInterface
     */
    protected $receiveEquipService;

    /**
     *
     * @var InputFilterInterface
     */
    protected $receiveEquipInputFilter;

    /**
     *
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     *
     * @var int The version of the receive equipment form to handle
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
    Twig $view, AssetServiceInterface $assetsService, ReceiveEquipServiceInterface $receiveEquipService, InputFilterInterface $receiveEquipInputFilter, AuthenticationServiceInterface $authService, $successUrl, $version, $container
    ) {
        $this->view = $view;
        $this->assetsService = $assetsService;
        $this->receiveEquipService = $receiveEquipService;
        $this->receiveEquipInputFilter = $receiveEquipInputFilter;
        $this->authService = $authService;
        $this->successUrl = $successUrl;
        $this->version = $version;
        $this->container = $container;
    }

    public function __invoke(Request $req, Response $res) {
        $school = $req->getAttribute('school');

        if ($req->isPost()) {
            $reqParams = $req->getParams();
            array_splice($reqParams['items'], 0, 0);
            $this->receiveEquipInputFilter->setData(array_merge($reqParams, [
                'school_id' => $school->id,
                'submitted_by' => $this->authService->getIdentity()->mail,
            ]));
            $isValid = $this->receiveEquipInputFilter->isValid();
            if ($isValid) {
                $data = $this->receiveEquipInputFilter->getValues();
                $receiveEquip = $this->receiveEquipService->submit($data);
                $_SESSION['receiveEquipForm']['receiveEquip'] = $receiveEquip;
                $res = $res->withRedirect($this->successUrl);

                return $res;
            }

            $this->view['form'] = [
                'is_valid' => $isValid,
                'values' => $this->receiveEquipInputFilter->getValues(),
                'raw_values' => $this->receiveEquipInputFilter->getRawValues(),
                'messages' => $this->receiveEquipInputFilter->getMessages(),
            ];
        }

        $loadForm = (bool) $req->getParam('load', false);
        $this->view['choose'] = !$loadForm && !$req->isPost();
        if (!$req->isPost() && $loadForm) {
            if (null !== ($receiveEquip = $this->receiveEquipService->findSchoolReceiveEquip($school->id))) {
                $this->view['form'] = [
                    'values' => $receiveEquip,
                ];
            }
        }
            return $res;
          }

}
