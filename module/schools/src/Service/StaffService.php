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
    private $filter;

    public function __construct(callable $filter)
    {
        $this->filter = $filter;
    }

    public function createTeacher(array $data)
    {
        $data = call_user_func($this->filter, $data, true);
        var_dump($data);
        die();
        unset($data['id']);
        $teacher  = R::dispense('teacher');
        $required = ['school_id', 'name','email', 'surname', 'telephone',
                     'position', 'branch_id', ];

        foreach ($required as $value) {
            if (array_key_exists($value, $data)) {
                $teacher[$value] = $data[$value];
            } else {
                return -1;
            }
        }
        $id = R::store($teacher);

        return $id;
    }

    public function updateTeacher(array $data, $id)
    {
        try {
            $teacher = R::load('teacher', $id);
            foreach ($data as $key => $value) {
                $teacher[$key] = $value;
            }
            $id = R::store($teacher);

            return $id;
        } catch (\Exception $e) {

        }
    }

    public function getTeacherById($id)
    {
        $teacher = R::load('teacher', $id);

        return $this->export($teacher);
    }

    public function getTeachersBySchoolId($id)
    {
        $teachers = R::findAll('teacher', 'school_id = ?', [$id]);

        return array_map([$this, 'export'], $teachers);
    }

    public function getBranches()
    {
        return array_map(function ($branch) {
            return $branch->export();
        }, R::findAll('branch', 'ORDER BY name ASC'));
    }

    private function export($teacherBean)
    {
        $position = [];
        if ($teacherBean->is_principle) {
            $position[] = 'Διευθυντής';
        }
        if ($teacherBean->is_responsible) {
            $position[] = 'Υπεύθυνος εργαστηρίου';
        }
        return array_merge($teacherBean->export(), [
            'branch' => $teacherBean->branch->name,
            'position' => implode(', ', $position),
        ]);
    }
}
