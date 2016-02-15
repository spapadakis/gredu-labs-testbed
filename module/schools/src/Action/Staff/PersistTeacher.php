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

class PersistTeacher
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

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $params              = $req->getParams();
        $params['school_id'] = $school->id;
        $id                  = $params['id'];
        unset($params['id']);

        try {
            if ($id) {
                $teacher = $this->staffService->updateTeacher($params, $id);
                $res     = $res->withStatus(200);
            } else {
                $teacher = $this->staffService->createTeacher($params);
                $res     = $res->withStatus(201);
            }
            $res = $res->withJson($teacher);
        } catch (Exception $ex) {
            $res = $res->withStatus(500, $ex->getMessage());
        }

        return $res;
    }
}
