<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action\Lab;

use GrEduLabs\Schools\Service\LabServiceInterface;
use GrEduLabs\Schools\Service\StaffServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ListAll
{
    /**
     *
     * @var Twig
     */
    protected $view;

    /**
     *
     * @var LabServiceInterface
     */
    protected $labService;

    /**
     *
     * @var StaffServiceInterface
     */
    protected $staffService;

    public function __construct(
        Twig $view,
        LabServiceInterface $labservice,
        StaffServiceInterface $staffservice
    ) {
        $this->view         = $view;
        $this->labService   = $labservice;
        $this->staffService = $staffservice;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }

        $labs = $this->labService->getLabsBySchoolId($school->id);

        return $this->view->render($res, 'schools/labs.twig', [
            'labs'      => $labs,
            'staff'     => array_map(function ($teacher) {
                return ['value' => $teacher['id'], 'label' => $teacher['fullname']];
            }, $this->staffService->getTeachersBySchoolId($school->id)),
            'network_options' => array_map(function ($option) {
                return ['value' => $option, 'label' => $option];
            }, $this->labService->getHasNetworkValues()),
            'server_options' => array_map(function ($option) {
                return ['value' => $option, 'label' => $option];
            }, $this->labService->getHasServerValues()),
            'lab_types' => array_map(function ($type) {
                return ['value' => $type['id'], 'label' => $type['name']];
            }, $this->labService->getLabTypes()),
            'lessons_options' => array_map(function ($lesson) {
                return ['value' => $lesson['id'], 'label' => $lesson['name']];
            }, $this->labService->getLessons()),
        ]);
    }
}
