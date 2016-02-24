<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Service;

interface LabServiceInterface
{
    public function createLab(array $data);
    public function updateLab(array $data, $id);
    public function getLabById($id);
    public function getLabsBySchoolId($id);
    public function getLabForSchool($school_id, $id);

    public function removeLabAttachment($lab_id);

    public function getLessons();
    public function getLessonsByLabId($id);

    public function getLabTypes();

    public function getHasNetworkValues();
    public function getHasServerValues();
}
