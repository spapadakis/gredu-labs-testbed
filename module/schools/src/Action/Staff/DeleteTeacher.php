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

use Exception;
use GrEduLabs\Schools\Service\StaffServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class DeleteTeacher
{
    /**
     *
     * @var StaffServiceInterface
     */
    private $staffService;

    public function __construct(StaffServiceInterface $staffService)
    {
        $this->staffService = $staffService;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school = $req->getAttribute('school', false);
        if (!$school->id) {
            return $res->withStatus(403, 'No school');
        }
        $id = $req->getParam('id', false);

        if (!$id) {
            $res = $res->withStatus(404);

            return $res;
        }

        $teacher = $this->staffService->getTeacherById($id);
        if ($teacher['school_id'] != $school->id) {
            $res = $res->withStatus(403, 'Schools not match');

            return $res;
        }

        try {
            $this->staffService->removeTeacher($id);
            $res = $res->withStatus(204);
        } catch (Exception $ex) {
            $res = $res->withStatus(500, $ex->getMessage());
        }

        return $res;
    }
}
