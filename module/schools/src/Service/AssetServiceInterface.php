<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Service;

interface AssetServiceInterface
{
    public function createItemCategory($name);
    public function changeItemCategoryName($id, $name);
    public function getAllItemCategories($version);
    public function findItemCategoryByName($name);
    public function getItemCategoryById($id);

    public function createExistingItem(array $data);
}
