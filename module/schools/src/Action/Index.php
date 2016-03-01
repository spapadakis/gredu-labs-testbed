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

use GrEduLabs\Schools\Service\LabServiceInterface;
use GrEduLabs\Schools\Service\SchoolAssetsInterface;
use GrEduLabs\Schools\Service\StaffServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class Index
{
    /**
     *
     * @var Twig
     */
    protected $view;

    /**
     *
     * @var StaffServiceInterface
     */
    protected $staffService;

    /**
     *
     * @var LabServiceInterface
     */
    protected $labService;

    /**
     *
     * @var SchoolAssetsInterface
     */
    protected $assetService;

    public function __construct(
        Twig $view,
        StaffServiceInterface $staffService,
        LabServiceInterface $labService,
        SchoolAssetsInterface $assetService
    ) {
        $this->view         = $view;
        $this->staffService = $staffService;
        $this->labService   = $labService;
        $this->assetService = $assetService;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school');

        return $this->view->render($res, 'schools/index.twig', [
            'school' => $school,
            'staff'  => array_reduce($this->staffService->getTeachersBySchoolId($school->id), function ($aggr, $teacher) {
                $name = sprintf('%s %s (%s)', $teacher['name'], $teacher['surname'], $teacher['branch']);
                if ($teacher['is_principle']) {
                    $aggr['principle'] = $name;
                } else {
                    $aggr['teachers'][] = $name;
                }

                return $aggr;
            }, []),
            'labs'   => $this->labService->getLabsBySchoolId($school->id),
            'assets' => array_reduce($this->assetService->getAssetsForSchool($school->id), function ($aggr, $asset) {
                $assetType = $asset['itemcategory_id'];
                if (!isset($aggr[$assetType])) {
                    $aggr[$assetType] = [
                        'category' => $asset['itemcategory'],
                        'count'    => 0,
                    ];
                }
                $aggr[$assetType]['count'] += $asset['qty'];

                return $aggr;
            }, []),
        ]);
    }
}
