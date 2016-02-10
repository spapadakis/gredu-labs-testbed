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
        $staff = $this->staffservice->getTeacherById(1);
        $res = $res->withJson($staff->export());
        return $res;
    }
}
