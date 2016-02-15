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
    public function createTeacher(array $data)
    {
        unset($data['id']);
        $teacher = R::dispense('teacher');
        $this->persist($teacher, $data);

        return $this->export($teacher);
    }

    public function updateTeacher(array $data, $id)
    {
        $teacher = R::load('teacher', $id);
        if (!$teacher->id) {
            throw new \InvalidArgumentException('No teacher found');
        }
        $this->persist($teacher, $data);

        return $this->export($teacher);
    }

    private function persist($teacher, array $data)
    {
        $teacher->school_id      = $data['school_id'];
        $teacher->name           = $data['name'];
        $teacher->surname        = $data['surname'];
        $teacher->telephone      = $data['telephone'];
        $teacher->email          = $data['email'];
        $teacher->branch_id      = $data['branch_id'];
        $teacher->is_principle   = isset($data['is_principle']);
        $teacher->is_responsible = isset($data['is_responsible']);

        R::store($teacher);
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

    public function removeTeacher($id)
    {
        R::trash('teacher', $id);
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
            'branch'   => $teacherBean->branch->name,
            'position' => implode(', ', $position),
        ]);
    }
}
