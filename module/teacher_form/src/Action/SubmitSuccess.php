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

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class SubmitSuccess
{
    protected $view;

    protected $formUrl;

    public function __construct(Twig $view, $formUrl)
    {
        $this->view    = $view;
        $this->formUrl = $formUrl;
    }

    public function __invoke(Request $req, Response $res)
    {

        if (!isset($_SESSION['teacherForm']['tForm'])) {
            $res = $res->withRedirect($this->formUrl);

            return $res;
        }
        $tForm = $_SESSION['teacherForm']['tForm'];

        $_SESSION['teacherForm']['tForm'] = null;
        unset($_SESSION['teacherForm']['tForm']);

        return $this->view->render($res, 'teacher_form/submit_success.twig', [
            
        ]);
    }
}





