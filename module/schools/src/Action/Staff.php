<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class Staff
{
    protected $view;

    public function __construct(Twig $view, $staffservice)
    {
        $this->view         = $view;
        $this->staffservice = $staffservice;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $staff = $this->staffservice->getTeachersBySchoolId(1);

        return $this->view->render($res, 'schools/staff.twig', [
            'staff'     => $staff,
            'branches' => array_map(function ($branch) {
                return ['value' => $branch['id'], 'label' => $branch['name']];
            }, $this->staffservice->getBranches()),
        ]);
    }
}
