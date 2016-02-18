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
use RedBeanPHP\R;

class ListAll
{
    protected $view;

    public function __construct(
        Twig $view, 
        LabServiceInterface $labservice,
        StaffServiceInterface $staffservice
    )
    {
        $this->view         = $view;
        $this->labservice   = $labservice;
        $this->staffservice = $staffservice;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        $school = $req->getAttribute('school', false);
        if (!$school) {
            return $res->withStatus(403, 'No school');
        }

       // $lab = R::dispense('lab');
       // $lab->school_id = 1;
       // $teacher = R::load('teacher', 2);
       // $lab->sharedCourse = array($course1, $course2);
       // $lab->area = 55;
       // $lab->in_school_use = true;
       // $lab->out_school_use = false;
       // $lab->attachment = 'foo/bar/qux/arxeio.gph';
       // $lab->has_network = true;
       // $lab->has_server = true;


       // R::store($lab);

        $labs = $this->labservice->getLabsBySchoolId($school->id);
        $staff = $this->staffservice->getTeachersBySchoolId($school->id);
        $clean_staff = [];
        foreach ($staff as $obj) {
            if ($obj['is_responsible']){
                $clean_staff[] = [
                    'value' => $obj['id'],
                    'label' => $obj['name']." ".$obj['surname']
                    ];
            }
        }
        $courses = $this->labservice->getCourses();
        $lessons = [];
        foreach ($courses as $lesson){
            $lessons[] = ['value' => $lesson->id, 'label' => $lesson->name];
        }
        error_log(print_r($courses,TRUE));
        error_log(print_r('courses',TRUE));
        return $this->view->render($res, 'schools/labs.twig', [
            'labs' => $labs ,
            'staff' => $clean_staff,
            'lab_types' => [
                [
                    'value' => 1,
                    'label' => 'ΕΡΓΑΣΤΗΡΙΟ',
                ],
                [
                    'value' => 2,
                    'label' => 'ΑΙΘΟΥΣΑ',
                ],
                [
                    'value' => 3,
                    'label' => 'ΓΡΑΦΕΙΟ',
                ],
            ],
            'lessons' => $lessons,
        ]);
    }
}
