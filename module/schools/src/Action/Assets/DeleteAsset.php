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

class DeleteAsset
{
    /**
     *
     * @var SchoolAssetsInterface
     */
    private $schoolAssetsService;

    public function __construct(SchoolAssetsInterface $schoolAssetsService)
    {
        $this->schoolAssetsService = $schoolAssetsService;
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

        try {
            $this->schoolAssetsService->removeAssetFromSchool($school->id, $id);
            $res = $res->withStatus(204);
        } catch (Exception $ex) {
            $res = $res->withStatus(500, $ex->getMessage());
        }

        return $res;
    }
}
