<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Service;

use RedBeanPHP\R;

class LabService implements LabServiceInterface
{
    protected $schoolService;
    protected $staffService;

    public function __construct(
        SchoolServiceInterface $schoolService,
        StaffServiceInterface $staffService
    ) {
        $this->schoolService = $schoolService;
        $this->staffService  = $staffService;
    }

    public function createLab(array $data)
    {
        error_log(print_r('creating', TRUE));
        unset($data['id']);
        $lab = R::dispense('lab');
        $this->persist($lab, $data);

        //return $this->export($lab);
        return $lab;
    }

    public function updateLab(array $data, $id)
    {
        $lab = R::load('lab', $id);
        if (!$lab->id) {
            throw new \InvalidArgumentException('No lab found');
        }
        $this->persist($lab, $data);

        return $this->export($lab);
    }

    private function persist($lab, $data)
    {
        R::debug(TRUE);
        error_log(print_r($data, TRUE));
        $lab->school_id       = $data['school_id'];
        $lab->name            = $data['name'];
        $lab->type            = $data['type'];
        $lab->area            = $data['area'];
        $lab->sharedCourse    = $this->getCoursesById($data['lessons']);
        $lab->out_school_use  = $data['use_ext_program'];
        $lab->in_school_use   = $data['use_in_program'];
        $lab->attachment      = 'attachment';
        $lab->has_network     = isset($data['has_network']);
        $lab->has_server      = isset($data['has_server']);
        $lab->responsible     = $data['responsible'];
        
        $id = R::store($lab);
    }

    public function getLabById($id)
    {
        $lab = R::load('lab', $id);

        return $lab;
    }

    public function getLabsBySchoolId($id)
    {
        $labs = R::findAll('lab', 'school_id = ?', [$id]);

        return array_map([$this, 'export'], $labs);
    }

    public function getCourses()
    {
        $courses = R::findAll('course');

        return  $courses;
    }

    public function getCoursesByLabId($id)
    {
        $lab     = R::load('lab', $id);
        $courses = $lab->sharedCourse;

        return $courses;
    }

    private function getCoursesById(array $ids)
    {
        $courses = [];
        foreach ($ids as $id) {
            $course    = R::load('course', $id);
            $courses[] = $course;
        }

        return $courses;
    }
}
