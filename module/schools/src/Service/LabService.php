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
        $lab      = R::dispense('lab');
        $required = ['school_id', 'name', 'type', 'area', 'in_school_use', 'out_school_use',
                     'courses', 'attachment', 'has_network', 'has_server', ];
        foreach ($required as $value) {
            if (array_key_exists($value, $data)) {
                $lab[$value] = $data[$value];
            } else {
                return -1;
            }
        }

        if (array_key_exists('teacher_id', $data)) {
            $lab['teacher_id'] = $data['teacher_id'];
        }

        $id = R::store($lab);

        return $id;
    }

    public function updateLab(array $data, $id)
    {
        $lab= R::load('lab', $id);
        foreach ($data as $key => $value) {
            $lab[$key] = $value;
        }
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
        $labs = R::findAll('lab', 'school_id = ?', [$id]);

        return array_map([$this, 'export'], $labs);
    }
}
