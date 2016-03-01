<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Service;

use InvalidArgumentException;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class LabService implements LabServiceInterface
{
    private $filesPath;

    public function __construct($filesPath)
    {
        $this->filesPath = $filesPath;
    }

    public function createLab(array $data)
    {
        unset($data['id']);
        $lab = R::dispense('lab');
        $this->persist($lab, $data);

        return $this->exportLab($lab);
    }

    public function updateLab(array $data, $id)
    {
        $lab = R::load('lab', $id);
        if (!$lab->id) {
            throw new InvalidArgumentException('No lab found');
        }
        $this->persist($lab, $data);

        return $this->exportLab($lab);
    }

    private function persist($lab, $data)
    {
        $lab->school_id           = $data['school_id'];
        $lab->name                = $data['name'];
        $lab->labtype_id          = $data['labtype_id'];
        $lab->area                = $data['area'];
        $lab->sharedLesson        = $this->getLessonsById($data['lessons']);
        $lab->use_ext_program     = $data['use_ext_program'];
        $lab->use_in_program      = $data['use_in_program'];
        $lab->has_network         = $data['has_network'];
        $lab->has_server          = $data['has_server'];
        $lab->responsible         = R::load('teacher', $data['responsible_id']);
        R::store($lab);
        $this->storeAttachement($lab, $data['attachment']);
        R::store($lab);
    }

    public function getLabById($id)
    {
        $lab = R::load('lab', $id);
        if (!$lab->id) {
            throw new InvalidArgumentException('No lab found');
        }

        return $this->exportLab($lab);
    }

    public function getLabsBySchoolId($id)
    {
        $labs = R::findAll('lab', 'school_id = ?', [$id]);

        return array_map([$this, 'exportLab'], $labs);
    }

    public function getLessons()
    {
        $lessons = R::findAll('lesson');

        return array_map(function ($lesson) {
            return $lesson->export();
        }, $lessons);
    }

    public function getLessonsByLabId($id)
    {
        $lab     = R::load('lab', $id);
        $lessons = $lab->sharedLesson;

        return array_map(function ($lesson) {
            return $lesson->export();
        }, $lessons);
    }

    public function getLabTypes()
    {
        return array_map(function ($lab) {
            return $lab->export();
        }, R::find('labtype'));
    }

    private function getLessonsById($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        return array_values(R::loadAll('lesson', $ids));
    }

    private function exportLab($bean)
    {
        $responsible = $bean->fetchAs('teacher')->responsible;
        if ($responsible) {
            $responsible = sprintf("%s %s", $responsible->name, $responsible->surname);
        }

        return array_merge($bean->export(), [
            'labtype'     => $bean->labtype->name,
            'responsible' => $responsible,
            'lessons'     => array_reduce($bean->sharedLesson, function ($ids, $lesson) {
                $ids[] = $lesson->id;

                return $ids;
            }, []),

        ]);
    }

    private function storeAttachement(OODBBean $lab, array $attachment = null)
    {
        if (null === $attachment) {
            return;
        }

        $schoolPath = $lab->school->registry_no . '/lab_' . $lab->id;
        $fullPath   = $this->filesPath . '/' . $schoolPath;
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0700, true);
        }
        // remove previous
        unlink($this->filesPath . '/' . $lab->attachment);
        // move new file 
        rename($attachment['tmp_name'], $fullPath . '/' . $attachment['name']);
        $lab->attachment      = $schoolPath . '/' . $attachment['name'];
        $lab->attachment_mime = $attachment['type'];

        return $lab;
    }

    public function getHasNetworkValues()
    {
        return [
           'ΔΟΜΗΜΕΝΗ ΚΑΛΩΔΙΩΣΗ',
           'ΑΣΥΡΜΑΤΗ ΔΙΚΤΥΟ ΜΕΣΩ WIFI',
           'ΔΕΝ ΥΠΑΡΧΕΙ ΔΙΚΤΥΟ',
        ];
    }

    public function getHasServerValues()
    {
        return ['ΝΑΙ', 'ΟΧΙ'];
    }

    public function getLabForSchool($school_id, $id)
    {
        $lab = R::findOne('lab', ' school_id = ? AND id = ? ', [
            $school_id,
            $id,
        ]);
        if (!$lab) {
            return;
        }

        return $this->exportLab($lab);
    }

    public function removeLabAttachment($lab_id)
    {
        $lab = R::load('lab', $lab_id);
        if (!$lab->id) {
            throw new InvalidArgumentException('No lab');
        }

        if (!$lab->attachment) {
            return ;
        }

        if (is_writable($this->filesPath . '/' . $lab->attachment)) {
            unlink($this->filesPath . '/' . $lab->attachment);
        }

        $lab->attachment      = null;
        $lab->attachment_mime = null;
        R::store($lab);
    }

    public function removeLab($id, $school_id = null)
    {
        $sql      = ' id = ? ';
        $bindings = [(int) $id];
        if (null !== $school_id) {
            $sql .= ' AND school_id = ? ';
            $bindings[] = (int) $school_id;
        }
        $lab = R::findOne('lab', $sql, $bindings);
        if (null !== $lab) {
            if ($lab->attachment && is_writable($this->filesPath . '/' . $lab->attachment)) {
                unlink($this->filesPath . '/' . $lab->attachment);
            }
            R::trash($lab);
        }
    }
}
