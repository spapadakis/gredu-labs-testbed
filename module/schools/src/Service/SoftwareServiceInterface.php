<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Service;

interface SoftwareServiceInterface
{
    public function createSoftwareCategory($name);
    public function getSoftwareCategoryById($id);
    public function getSoftwareCategories();
    public function updateSoftwareCategory($id, $data);
    public function createSoftware(array $data);
    public function updateSoftware(array $data, $id);
    public function getSoftwareById($id);
    public function getSoftwareBySchoolId($id);
    public function getSoftwareByLabId($id);

}
