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

use GrEduLabs\Schools\Service\AssetServiceInterface;
use GrEduLabs\Schools\Service\LabServiceInterface;
use GrEduLabs\Schools\Service\SchoolAssetsInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ListAssets
{
    /**
     *
     * @var Twig
     */
    protected $view;

    /**
     *
     * @var AssetServiceInterface
     */
    protected $assetsService;

    /**
     *
     * @var SchoolAssetsInterface
     */
    protected $schoolAssetsService;

    /**
     *
     * @var LabServiceInterface
     */
    protected $labService;

    public function __construct(
        Twig $view,
        AssetServiceInterface $assetsService,
        SchoolAssetsInterface $schoolAssetsService,
        LabServiceInterface $labService
    ) {
        $this->view                = $view;
        $this->assetsService       = $assetsService;
        $this->schoolAssetsService = $schoolAssetsService;
        $this->labService          = $labService;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $assets         = $this->schoolAssetsService->getAssetsForSchool($school->id);
        $itemCategories = $this->assetsService->getAllItemCategories();
        $labs           = $this->labService->getLabsBySchoolId($school->id);

        return $this->view->render($res, 'schools/assets.twig', [
            'assets'          => $assets,
            'item_categories' => array_map(function ($category) {
                return ['value' => $category['id'], 'label' => $category['name'] ];
            }, $itemCategories),
            'labs' => array_map(function ($lab) {
                return ['value' => $lab['id'], 'label' => $lab['name']];
            }, $labs),
        ]);
    }
}
