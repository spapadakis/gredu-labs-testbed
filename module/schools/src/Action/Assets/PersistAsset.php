<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action\Assets;

use Exception;
use GrEduLabs\Schools\Service\SchoolAssetsInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class PersistAsset
{
    private $schoolAssetsService;

    public function __construct(SchoolAssetsInterface $schoolAssetsService)
    {
        $this->schoolAssetsService = $schoolAssetsService;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $params           = $req->getParams();
        $params['lab_id'] = 1;
        $id               = $params['id'];
        unset($params['id']);

        try {
            if ($id) {
                $asset = $this->schoolAssetsService->updateAssetForSchool(
                    $school->id,
                    $params,
                    $id
                );
                $res   = $res->withStatus(200);
            } else {
                $asset = $this->schoolAssetsService->addAssetForSchool(
                    $school->id,
                    $params
                );
                $res   = $res->withStatus(201);
            }
            $res = $res->withJson($asset);
        } catch (Exception $ex) {
            $res = $res->withStatus(500, $ex->getMessage());
        }

        return $res;
    }
}
