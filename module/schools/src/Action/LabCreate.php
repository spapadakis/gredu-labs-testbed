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

class LabCreate
{

    public function __construct($labservice)
    {
        $this->labservice = $labservice;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $params = $req->getParams();
        if (array_key_exists('id', $params)){ 
            $id = $params['id'];
            unset($params['id']);
            $id = $this->labservice->updateLab($params, $id);
            $lab = $this->labservice->getLabById($id);
        }
        else{
            $id = $this->labservice->createLab($params);
            $lab = $this->labservice->getLabById($id);
        }

        $res = $res->withJson($lab->export());
        return $res;
    }
}
