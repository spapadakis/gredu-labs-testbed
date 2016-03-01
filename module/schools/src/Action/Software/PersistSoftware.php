<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action\Software;

use Exception;
use GrEduLabs\Schools\Service\SoftwareServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class PersistSoftware
{
    /**
     *
     * @var SoftwareServiceInterface
     */
    private $softwareService;

    public function __construct(SoftwareServiceInterface $softwareService)
    {
        $this->softwareService = $softwareService;
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
                $software= $this->softwareService->updateSoftware($params, $id);
                $res     = $res->withStatus(200);
            } else {
                $software= $this->softwareService->createSoftware($params);
                $res     = $res->withStatus(201);
            }
            $res = $res->withJson($software);
        } catch (Exception $ex) {
            $res = $res->withStatus(500, $ex->getMessage());
        }

        return $res;
    }
}
