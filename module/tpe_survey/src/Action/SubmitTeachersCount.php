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

use GrEduLabs\TpeSurvey\Service\SurveyServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Description of SubmitTeachersCount
 *
 * @author kanellov
 */
class SubmitTeachersCount
{
    /**
     *
     * @var SurveyServiceInterface
     */
    private $service;

    public function __construct(SurveyServiceInterface $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $req, Response $res)
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }
        $this->service->setTotalTeachers($school->id, (int) $req->getParam('total_teachers', 0));

        return $res->withStatus(204);
    }
}
