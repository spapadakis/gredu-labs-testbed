<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\TeacherForm\Action;

use GrEduLabs\TeacherForm\Service\TeacherFormServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\InputFilter\InputFilterInterface;



class TeacherForm
{
    /**
     * @var Twig
     */
    protected $view;
    /**
     *
     * @var TeacherFormServiceInterface
     */
    protected $TeacherFormService;
    /**
     *
     * @var InputFilterInterface
     */
    protected $TeacherFormInputFilter;
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

    public function __construct(
    Twig $view, TeacherFormServiceInterface $TeacherFormService,InputFilterInterface $TeacherFormInputFilter, $successUrl,$container

    ) {
        $this->view                     = $view;
        $this->TeacherFormService       = $TeacherFormService;
        $this->TeacherFormInputFilter   = $TeacherFormInputFilter;
        $this->successUrl               = $successUrl;
        $this->container                = $container;
    }

    public function __invoke(Request $req, Response $res)
    {

        if ($req->isPost()) {
            $reqParams = $req->getParams();

            $this->TeacherFormInputFilter->setData($reqParams);
            $isValid = $this->TeacherFormInputFilter->isValid();
     
            if ($isValid) {
                $data = $this->TeacherFormInputFilter->getValues();
                $TeacherForm = $this->TeacherFormService->submit($data);
                $_SESSION['teacherForm']['tForm'] = $TeacherForm;
                $res = $res->withRedirect($this->successUrl);
                return $res;
            }
             else 
                print_r("lalalalalal");
               
             
             
        } 
        $res = $this->view->render($res, 'teacher_form/form.twig', [
            'branches'  => array_map(function ($branch) {
                return ['value' => $branch['id'], 'label' => $branch['name']];
            }, $this->TeacherFormService->getBranches()),
        ]);

       return $res;

    }

}