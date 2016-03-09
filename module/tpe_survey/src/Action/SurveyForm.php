<?php

/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\TpeSurvey\Action;

use GrEduLabs\Schools\Service\StaffService;
use GrEduLabs\Schools\Service\StaffServiceInterface;
use GrEduLabs\TpeSurvey\Service\SurveyServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class SurveyForm
{
    /**
     *
     * @var SurveyServiceInterface
     */
    private $service;

    /**
     *
     * @var callable
     */
    private $inputFilter;

    /**
     *
     * @var StaffService
     */
    private $staffService;

    public function __construct(
        SurveyServiceInterface $service,
        callable $inputFilter,
        StaffServiceInterface $staffService
    ) {
        $this->service      = $service;
        $this->inputFilter  = $inputFilter;
        $this->staffService = $staffService;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $teacherId = $req->getParam('teacher_id');
        $teacher   = $this->staffService->getTeacherById($teacherId);
        if ($teacher['school_id'] !== $school->id) {
            return $res->withStatus(403, 'No school');
        }

        if ($req->isPost()) {
            $inputFilter = $this->inputFilter;
            $result      = $inputFilter($req->getParams());
            if (!$result['is_valid']) {
                $res = $res->withStatus(422);
                $res = $res->withJson($result);

                return $res;
            }
            $this->service->saveAnswers($teacherId, $result['values']);
        }
        $data = $this->service->getAnswers($teacherId);
        $res  = $res->withJson($data);

        return $res;
    }
}
