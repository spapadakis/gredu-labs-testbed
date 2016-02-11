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

class StaffService implements StaffServiceInterface
{
    protected $schoolService;
    public function __construct(SchoolServiceInterface $schoolService)
    {
        $this->schoolService = $schoolService;
    }

    public function createTeacher(array $data)
    {
        $teacher  = R::dispense('teacher');
        $required = ['school_id', 'name', 'surname', 'phoneNumber', 'labSupervisor', 'schoolPrincipal'];
        $optional = ['speciality', 'email'];
        foreach ($required as $value) {
            if (array_key_exists($value, $data)) {
                $teacher[$value] = $data[$value];
            } else {
                return -1;
            }
        }
        foreach ($optional as $value) {
            if (array_key_exists($value, $data)) {
                $teacher[$value] = $data[$value];
            } else {
                $$value = '';
            }
        }
        $school          = $this->schoolService->getSchool($teacher['school_id']);
        $teacher->school = $school;
        $id              = R::store($teacher);
        return $id;
    }

    public function updateTeacher(array $data, $id){
        $teacher = R::load('teacher', $id);
        foreach ($data as $key => $value){
            $teacher[$key] = $value;
        }
        $id = R::store($teacher);
        return $id;
    }

    public function getTeacherById($id)
    {
        $teacher = R::load('teacher', $id);
        return $teacher;
    }

    public function getTeachersBySchoolId($id)
    {
        $school   = $this->schoolService->getSchool($id);
        $teachers = $school->ownTeacher;
        return $teachers;
    }
}
