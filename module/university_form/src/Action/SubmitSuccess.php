<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\UniversityForm\Action;

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
        $univ = $req->getAttribute('univ');

        if (!isset($_SESSION['UniversityForm']['univForm'])) {
            $res = $res->withRedirect($this->formUrl);

            return $res;
        }
        $univForm = $_SESSION['UniversityForm']['univForm'];

        $_SESSION['UniversityForm']['univForm'] = null;
        unset($_SESSION['UniversityForm']['univForm']);

        return $this->view->render($res, 'university_form/submit_success.twig', [
            'appForm' => $appForm,
        ]);
    }
}
