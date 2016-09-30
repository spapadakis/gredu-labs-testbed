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

class AssetService implements AssetServiceInterface, SchoolAssetsInterface
{
    public function changeItemCategoryName($id, $name)
    {
        $category = R::load('itemcategory', $id);
        if (!$category->id) {
            throw new InvalidArgumentException('No itemcategory found');
        }
        $category->name = $name;
        R::store($category);

        return $category;
    }

    public function createExistingItem(array $data)
    {
        /// ???
    }

    public function createItemCategory($name)
    {
        $category       = R::dispense('itemcategory');
        $category->name = $name;
        $id             = R::store($category);

        return $this->exportItemCategory($category);
    }

    public function findItemCategoryByName($name)
    {
        $category = R::findOne('itemcategory', 'name = ?', [$name]);
        if ($category) {
            return $this->exportItemCategory($category);
        }

        return;
    }

 /*   public function getAllItemCategories()
    {
        return array_values(array_map(
            [$this, 'exportItemCategory'],
            R::findAll('itemcategory', ' ORDER BY sort ASC ')
        ));
    } */
    
    public function getAllItemCategories()
    {
        // current version 1 = current group of items to fetch, groupflag must be 1
        return array_values(array_map(
            [$this, 'exportItemCategory'],
            R::find('itemcategory', ' groupflag = 1 ORDER BY sort ASC ', []) 
        ));
    }

    public function getItemCategoryById($id)
    {
        $category = R::load('itemcategory', $id);
        if (!$category->id) {
            throw new InvalidArgumentException('Invalid itemcategory id');
        }

        return $this->exportItemCategory($category);
    }

    public function getAssetsForSchool($school_id, array $filters = [])
    {
        $sql      = [' school_id = ? '];
        $bindings = [(int) $school_id];

        if (isset($filters['itemcategory_id'])) {
            $sql[]      = ' itemcategory_id = ? ';
            $bindings[] = (int) $filters['itemcategory_id'];
        }

        if (isset($filters['lab_id'])) {
            $sql[]      = ' lab_id = ? ';
            $bindings[] = (int) $filters['lab_id'];
        }

        $assets = R::findAll('schoolasset', implode(' AND ', $sql), $bindings);

        return array_values(array_map([$this, 'exportSchoolAsset'], $assets));
    }

    public function addAssetForSchool($school_id, array $assetData)
    {
        $asset = $this->importSchoolAsset(R::dispense('schoolasset'), $assetData, $school_id);
        R::store($asset);

        return $this->exportSchoolAsset($asset);
    }

    public function updateAssetForSchool($school_id, array $assetData, $assetId)
    {
        $asset = R::findOne('schoolasset', ' id = ? AND school_id = ? ', [$assetId, $school_id]);
        if (!$asset) {
            throw new InvalidArgumentException('No school asset found');
        }

        $asset = $this->importSchoolAsset($asset, $assetData, $school_id);
        R::store($asset);

        return $this->exportSchoolAsset($asset);
    }

    public function removeAssetFromSchool($school_id, $assetId)
    {
        $asset = R::findOne('schoolasset', ' id = ? AND school_id = ? ', [$assetId, $school_id]);
        if (!$asset) {
            throw new InvalidArgumentException('No school asset found');
        }
        R::trash($asset);
    }

    private function exportItemCategory($bean)
    {
        return $bean->export();
    }

    private function exportSchoolAsset($bean)
    {
        return array_merge($bean->export(), [
            'lab'          => $bean->lab->name,
            'itemcategory' => $bean->itemcategory->name,
        ]);
    }

    private function importSchoolAsset($bean, array $data, $school_id)
    {
        $bean->itemcategory_id  = (int) $data['itemcategory_id'];
        $bean->school_id        = (int) $school_id;
        $bean->qty              = (int) $data['qty'];
        $bean->lab_id           = (int) $data['lab_id'];
        $bean->acquisition_year = $data['acquisition_year'];
        $bean->comments         = $data['comments'];

        return $bean;
    }
}
