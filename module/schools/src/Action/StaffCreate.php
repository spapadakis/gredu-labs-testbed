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

class StaffCreate
{

    public function __construct($staffservice)
    {
        $this->staffservice = $staffservice;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $params = $req->getParams();
        $id     = $params['id'];
        unset($params['id']);
        $params['school_id'] = $school->id;
        if ($id > 0) {
            $id      = $this->staffservice->updateTeacher($params, $id);
            $teacher = $this->staffservice->getTeacherById($id);
        } else {
            $id = $this->staffservice->createTeacher($params);
            if ($id > 0) {
                $teacher = $this->staffservice->getTeacherById($id);
            }
        }

        if (isset($teacher)) {
            return $res->withJson(array_merge($teacher->export(), [
                'branch' => $teacher->branch->name,
            ]))->withStatus(201);
        } else {
            return $res->withStatus(400);
        }
    }
}
