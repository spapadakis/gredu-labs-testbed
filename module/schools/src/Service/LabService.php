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
        $lab->school_id       = $data['school_id'];
        $lab->name            = $data['name'];
        $lab->type            = $data['type'];
        $lab->area            = $data['area'];
        $lab->sharedLesson[]    = $this->getLessonsById($data['lessons']);
        $lab->use_ext_program  = $data['use_ext_program'];
        $lab->use_in_program  = $data['use_in_program'];
        $lab->attachment      = $data['attachment'];
        $lab->has_network     = $data['has_network'];
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
        $elabs=[];
        foreach($labs as $lab) {
            $elabs[] = $lab->export();
        }
        return $elabs;
    }

    public function getLessons()
    {
        $lessons = R::findAll('lesson');

        return  $lessons;
    }

    public function getLessonsByLabId($id)
    {
        $lab     = R::load('lab', $id);
        $lessons = $lab->sharedLesson;

        return $lessons;
    }

    private function getLessonsById(array $ids)
    {
        $lessons= [];
        foreach ($ids as $id) {
            $lesson= R::load('lesson', $id);
            $lessons[] = $lesson;
        }

        return $lesson;
    }
}
