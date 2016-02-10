<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\School\Service;

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
        $this->staffService = $staffService;
    }
    public function createLab(array $data)
    {
        $lab = R::dispense('lab');
        $required = ['name', 'school', 'area'];
        foreach ($required as $value){
            if (array_key_exists($value, $data)){
                if ($value == 'school')
                {
                    $school_id = $data[$value];
                }
                else
                {
                $lab[$value] = $data[$value];
                }
            }
            else
            {
                return -1;
            }
        }
        if (array_key_exists('teacher', $data)) {
            $teacher_id = $data['teacher'];
        }
        $school = $this->schoolService->getSchool($school_id);
        $teacher = $this->staffService->getTeacherById($teacher_id);
        $lab->school = $school;
        $lab->teacher = $teacher;
        $id = R::store($lab);
        return $id;
    }
    public function getLabById($id)
    {
        $lab = R::load('lab', $id);
        return $lab;
    }
    public function getLabsBySchoolId($id)
    {
        $school = $this->schoolService->getSchool($id);
        $labs = $school->ownLab;
        return $labs;
    }
}
