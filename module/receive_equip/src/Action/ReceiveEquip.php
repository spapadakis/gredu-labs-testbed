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
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\InputFilter\InputFilterInterface;

class ReceiveEquip
{

    /**
     * @var Twig
     */
    protected $view;

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
     * @var flash messages
     */
    protected $flash;


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
    Twig $view, ReceiveEquipServiceInterface $receiveEquipService, InputFilterInterface $receiveEquipInputFilter, AuthenticationServiceInterface $authService, $successUrl, $flash, $container
    ) {
        $this->view                    = $view;
        $this->receiveEquipService     = $receiveEquipService;
        $this->receiveEquipInputFilter = $receiveEquipInputFilter;
        $this->authService             = $authService;
        $this->successUrl              = $successUrl;
        $this->flash                   = $flash;
        $this->container               = $container;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school                   = $req->getAttribute('school');
        $receivedDocumentFileName = $school->id;

        if ($req->isPost()) {
            $reqParams = $req->getParams();
            array_splice($reqParams['items'], 0, 0);

            $this->receiveEquipInputFilter->setData(array_merge($reqParams, [
                'school_id'    => $school->id,
                'submitted_by' => $this->authService->getIdentity()->mail,
            ]));
            $isValid = $this->receiveEquipInputFilter->isValid();

            if ($isValid) {
                $isValidFile = true;

                $files       = $req->getUploadedFiles();
                if (empty($files['received_document'])) {
                    $isValidFile = false;
                    $this->flash->addMessage('danger', "Πρέπει να επισυνάψετε το δελτίο παραλαβής και μετά να επιλέξετε Υποβολή");
//                    throw new Exception('Expected a newfile');
                } else {
                    $clientFile = $files['received_document'];

                    if ($clientFile->getError() === UPLOAD_ERR_OK) {
                        $this->container["logger"]->info(sprintf(
                                'mediatype = %s, filesize= %s',
                                $clientFile->getClientMediaType(), $clientFile->getSize()
                            ));

                        if ((int) $clientFile->getSize() > (int) $this->container['settings']['application_form']['file_upload_max_size']) {
                            $isValidFile = false;
                            $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε. Το μέγεθος του αρχείου υπερβαίνει το επιτρεπτό όριο");
                        } else {
                            $clientFileName    = $clientFile->getClientFilename();
                            $clientFileNameExt = strtolower(pathinfo($clientFileName, PATHINFO_EXTENSION));
                            if (in_array($clientFileNameExt, $this->container['settings']['application_form']['file_upload_extensions'])) {
                                $receivedDocumentFileName = $receivedDocumentFileName . "." . $clientFileNameExt;
                                $clientFile->moveTo($this->container['settings']['application_form']['file_upload_path'] . "/" . $receivedDocumentFileName);
                            } else {
                                $isValidFile = false;
                                $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε. Ο τύπος του αρχείου δεν είναι επιτρεπτός");
                            }
                        }
                    } else {
                        $isValidFile = false;
                        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
                    }
                }

                if ($isValidFile) {
                    $data                                         = $this->receiveEquipInputFilter->getValues();
                    $receiveEquip                                 = $this->receiveEquipService->submit($data, $receivedDocumentFileName);
                    $_SESSION['receiveEquipForm']['receiveEquip'] = $receiveEquip;
                    $res                                          = $res->withRedirect($this->successUrl);

                    return $res;
                } else {
                    return $res->withRedirect($req->getUri());
                }
            }

            $this->view['form'] = [
                'is_valid'        => $isValid,
                'values'          => $this->receiveEquipInputFilter->getValues(),
                'raw_values'      => $this->receiveEquipInputFilter->getRawValues(),
                'messages'        => $this->receiveEquipInputFilter->getMessages(),
            ];
        }

        if (!$req->isPost()) {
            if (null !== ($receiveEquip = $this->receiveEquipService->findSchoolReceiveEquip($school->id))) {
                $this->view['form'] = [
                    'school' => $school,
                    'exists' => true,
                    'values' => $receiveEquip,
                ];
            } else {
                $this->view['form'] = [
                    'school' => $school,
                    'exists' => false,
                    'values' => null,
                ];
            }
        }

        $res = $this->view->render($res, 'receive_equip/form.twig', [

                ]);

        return $res;
    }

    private function fileUpload($clientFile)
    {
        switch ($error) {
        case UPLOAD_ERR_OK:
            $this->container["logger"]->info(sprintf(
                    'mediatype = %s, filesize= %s',
                    $clientFile->getClientMediaType(), $clientFile->getSize()
                ));

            if ((int) $clientFile->getSize() > (int) $this->container['settings']['application_form']['file_upload_max_size']) {
                $isValidFile = false;
                $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε. Το μέγεθος του αρχείου υπερβαίνει το επιτρεπτό όριο");
            } else {
                $clientFileName    = $clientFile->getClientFilename();
                $clientFileNameExt = strtolower(pathinfo($clientFileName, PATHINFO_EXTENSION));
                if (in_array($clientFileNameExt, $this->container['settings']['application_form']['file_upload_extensions'])) {
                    $receivedDocumentFileName = $receivedDocumentFileName . "." . $clientFileNameExt;
                    $clientFile->moveTo($this->container['settings']['application_form']['file_upload_path'] . "/" . $receivedDocumentFileName);
                } else {
                    $isValidFile = false;
                    $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε. Ο τύπος του αρχείου δεν είναι επιτρεπτός");
                }
            }
            $response = 'There is no error, the file uploaded with success.';
            break;
        case UPLOAD_ERR_INI_SIZE:
        $isValidFile = false;
        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
            break;
        case UPLOAD_ERR_FORM_SIZE:
        $isValidFile = false;
        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            break;
        case UPLOAD_ERR_PARTIAL:
        $isValidFile = false;
        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'The uploaded file was only partially uploaded.';
            break;
        case UPLOAD_ERR_NO_FILE:
        $isValidFile = false;
        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'No file was uploaded.';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
        $isValidFile = false;
        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
            break;
        case UPLOAD_ERR_CANT_WRITE:
        $isValidFile = false;
        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
            break;
        case UPLOAD_ERR_EXTENSION:
        $isValidFile = false;
        $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'File upload stopped by extension. Introduced in PHP 5.2.0.';
            break;
        default:
            $isValidFile = false;
            $this->flash->addMessage('danger', "Η επισύναψη - αποστολή του αρχείου απέτυχε");
            $response = 'Unknown upload error';
            break;
          }
    }
}
