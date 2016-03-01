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
use RedBeanPHP\R;

class SoftwareService implements SoftwareServiceInterface
{

    public function createSoftwareCategory($name)
    {
        $software_category = R::dispense('softwarecategory');
        $software_category->name = $name;
        R::store($software_category);
    }

    public function getSoftwareCategoryById($id)
    {
        $software_category = R::load('softwarecategory', $id);
        return $software_category->export();
    }

    public function getSoftwareCategories()
    {
        $software_categories = R::findAll('softwarecategory');
        return $this->exportAll($software_categories);
    }

    public function updateSoftwareCategory($id, $data)
    {
        $software_category = R::load('softwarecategory');
        $software_category->name = $name;
        R::store($software_category);
    }

    public function createSoftware(array $data)
    {
        unset($data['id']);
        $software = R::dispense('software');
        $this->persistSoftware($software, $data);
        return $software->export();
    }

    public function updateSoftware(array $data, $id)
    {
        $software = R::load('software', $id);
        if (!$software->id) {
            throw new \InvalidArgumentException('No software found');
        }
        $this->persistSoftware($software, $data);
        return $software->export();
    }

    private function persistSoftware($software, array $data)
    {
        if (!$data['lab_id']){
            $data['lab_id'] = NULL;
        }
        
        $software->softwarecategory_id = $data['softwarecategory'];
        $software->school_id            = $data['school_id'];
        $software->lab_id               = $data['lab_id'];
        $software->title                = $data['title'];
        $software->vendor               = $data['vendor'];
        $software->url                  = $data['url'];
        
        R::store($software);
    }

    public function getSoftwareById($id)
    {
        $software = R::load('software', $id);
        return $software->export();
    }

    public function getSoftwareBySchoolId($id)
    {
        $software = R::findAll('software', 'school_id = ?', [$id]);
        return $this->exportAll($software);
    }

    public function getSoftwareByLabId($id)
    {
        $software = R::findAll('software', 'lab_id = ?', [$id]);
        return $software->exportAll();
    }

    public function removeSoftware($id)
    {
        R::trash('software', $id);
    }

    private function exportAll($beans)
    {
        $exported = [];
        foreach($beans as $bean)
        {
            $exported[] = $bean->export();
        }
        return $exported;
    }
}
