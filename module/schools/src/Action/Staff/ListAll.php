<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action\Staff;

use GrEduLabs\Schools\Service\StaffServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ListAll
{
    private $view;
    private $staffService;

    public function __construct(Twig $view, StaffServiceInterface $staffService)
    {
        $this->view         = $view;
        $this->staffService = $staffService;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $staff = $this->staffService->getTeachersBySchoolId($school->id);

        return $this->view->render($res, 'schools/staff.twig', [
            'school'    => $school,
            'staff'     => $staff,
            'branches'  => array_map(function ($branch) {
                return ['value' => $branch['id'], 'label' => $branch['name']];
            }, $this->staffService->getBranches()),
        ]);
    }
}
