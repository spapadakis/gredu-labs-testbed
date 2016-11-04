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
     * @var type SLIM application container
     */
    protected $container;

    /**
     *
     * @var string
     */
    protected $successUrl;

    /**
     *
     * @var array
     */
    protected $formErrorMessages;

    public function __construct(
    Twig $view, ReceiveEquipServiceInterface $receiveEquipService, InputFilterInterface $receiveEquipInputFilter, AuthenticationServiceInterface $authService, $successUrl, $container
    ) {
        $this->view                    = $view;
        $this->receiveEquipService     = $receiveEquipService;
        $this->receiveEquipInputFilter = $receiveEquipInputFilter;
        $this->authService             = $authService;
        $this->successUrl              = $successUrl;
        $this->container               = $container;
        $this->formErrorMessages       = [];
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
            $isValidFile = true;
            if ($isValid) {

                $files       = $req->getUploadedFiles();
                if (empty($files['received_document'])) {
                    $isValidFile = false;
                    array_push($this->formErrorMessages, "Πρέπει να επισυνάψετε το δελτίο παραλαβής και μετά να επιλέξετε Υποβολή");
                    $this->container["logger"]->info(sprintf('empty($files[received_document]) = true'));
                } else {
                    $clientFile  = $files['received_document'];
                    $receivedDocumentFileName = $this->fileUpload($clientFile, $receivedDocumentFileName);
                    if ($receivedDocumentFileName === null) {
                        $isValidFile = false;
                    }
                }

                if ($isValidFile === true) {
                    $data                                         = $this->receiveEquipInputFilter->getValues();
                    $receiveEquip                                 = $this->receiveEquipService->submit($data, $receivedDocumentFileName);
                    $_SESSION['receiveEquipForm']['receiveEquip'] = $receiveEquip;
                    $res                                          = $res->withRedirect($this->successUrl);
                    return $res;
                }
            }

            $this->populateInvalidForm($school);
        } else {    // not post request
            if (null !== ($receiveEquip = $this->receiveEquipService->findSchoolReceiveEquip($school->id))) {
                $this->view['form'] = [
                    'school' => $school,
                    'is_valid' => true,
                    'exists' => true,
                    'values' => $receiveEquip,
                ];
            } else {
                $this->view['form'] = [
                    'school' => $school,
                    'is_valid' => true,
                    'exists' => false,
                    'values' => null,
                ];
            }
        }

        $res = $this->view->render($res, 'receive_equip/form.twig', [

                ]);

        return $res;
    }

    private function populateInvalidForm($school) {
        $receiveEquip = $this->receiveEquipService->findSchoolReceiveEquip($school->id);
        $dataFromForm = $this->receiveEquipInputFilter->getValues();
        if ($receiveEquip !== null) {
            $items = $receiveEquip['items'];
            $dataItemsFromForm = $dataFromForm['items'];
            $dataItemsFromFormLength = count($dataItemsFromForm);
            foreach ($items as $item) {
              for ($i=0; $i<$dataItemsFromFormLength; $i++) {

                if ((int) $item['id'] === (int) $dataItemsFromForm[$i]['id']) {
                  $dataItemsFromForm[$i]['itemcategory'] = $item['itemcategory'];
                  $dataItemsFromForm[$i]['qty'] = $item['qty'];
                  $dataItemsFromForm[$i]['lab'] = $item['lab'];
                  break;
                }
              }

            }
            $dataFromForm['items'] = $dataItemsFromForm;
        }
        $this->view['form'] = [
            'school'            => $school,
            'exists'            => true,
            'is_valid'          => false,
            'values'            => $dataFromForm,
            'raw_values'        => $this->receiveEquipInputFilter->getRawValues(),
            'messages'          => $this->receiveEquipInputFilter->getMessages(),
            'formErrorMessages' => $this->formErrorMessages
        ];

    }

    private function fileUpload($clientFile, $receivedDocumentFileName)
    {
        $vf = true;
        $this->container["logger"]->info(sprintf(
                'error code= %d, mediatype = %s, filesize= %s, sizepermitted= %s',
                $clientFile->getError(), $clientFile->getClientMediaType(), $clientFile->getSize(), $this->container['settings']['receive_equip']['file_upload_max_size']
            ));

        switch ($clientFile->getError()) {
        case UPLOAD_ERR_OK:
            if ((int) $clientFile->getSize() > (int) $this->container['settings']['receive_equip']['file_upload_max_size']) {
                $vf = false;
                array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Το μέγεθος του αρχείου υπερβαίνει το επιτρεπτό όριο της εφαρμογής Edulabs");
            } else {
                $clientFileName    = $clientFile->getClientFilename();
                $clientFileNameExt = strtolower(pathinfo($clientFileName, PATHINFO_EXTENSION));
                if (in_array($clientFileNameExt, $this->container['settings']['receive_equip']['file_upload_types_permitted'])) {
                    $receivedDocumentFileName = $receivedDocumentFileName . "." . $clientFileNameExt;

                    try {
                        $clientFile->moveTo($this->container['settings']['receive_equip']['file_upload_path'] . "/" . $receivedDocumentFileName);
                    }
                    catch (InvalidArgumentException $e) {
                        array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Η διαδρομή προορισμού δεν υπάρχει");
                        $vf = false;
                    }
                    catch (RuntimeException $e) {
                        array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Σφάλμα στην μετακίνηση του αρχείου");
                        $vf = false;
                    }

                } else {
                    $vf = false;
                    array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Ο τύπος του αρχείου δεν είναι επιτρεπτός");
                }
            }
            break;
        case UPLOAD_ERR_INI_SIZE:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Το μέγεθος του αρχείου υπερβαίνει το επιτρεπτό όριο");
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Το μέγεθος του αρχείου υπερβαίνει το επιτρεπτό όριο της φόρμας παραλαβής");
            break;
        case UPLOAD_ERR_PARTIAL:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Μόνο τμήμα του αρχείου αποστάλθηκε. Προσπαθήστε πάλι");
            break;
        case UPLOAD_ERR_NO_FILE:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Το αρχείο δεν βρέθηκε. Προσπαθήστε πάλι");
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Δεν υπάρχει προσωρινός χώρος αποθήκευσης");
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Αποτυχία αποθήκευσης");
            break;
        case UPLOAD_ERR_EXTENSION:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Η αποστολή τερματίστηκε πρόωρα");
            break;
        default:
            $vf = false;
            array_push($this->formErrorMessages, "Η επισύναψη - αποστολή του αρχείου απέτυχε. Απροσδιόριστο σφάλμα");
            break;
        }

        if ($vf === true) {
            return $receivedDocumentFileName;
        }
        else {
            return null;
        }
    }
}
