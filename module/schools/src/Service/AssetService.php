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

class AssetService implements AssetServiceInterface
{
    protected $schoolService;
    protected $labService;

    public function __construct(
        SchoolServiceInterface $schoolservice, 
        LabService $labservice
    ) {
        $this->schoolservice = $schoolservice;
        $this->labservice = $labservice;
    }

    public function createItemCategory($name){
        $item_category = R::dispense('itemcategory');
        $item_category->name = $name;
        $id = R::store($item_category);
        return $id;
    }
    public function getAllItemCategories(){
        $categories = R::findAll('itemcategory');
        return $categories;
    }
    public function getItemCategoryByName($name){
        $cat = R::findOne('itemcategory', 'name = ?', [$name]);
        return $cat;
    }
    public function getItemCategoryById($id){
        $cat = R::load('itemcategory', $id);
        return $cat;
    }
    //Software Category Actions
    public function createSoftwareCategory(array $data){
        $soft_category = R::dispense('softwarecategory');
        $soft_category->name = $data['name'];
        $soft_category->manufacturer = $data['manufacturer'];
        $soft_category->website = $data['website'];
        $id = R::store($soft_category);
        return $id;
    }
    public function getAllSoftwareCategories(){
        $categories = R::findAll('softwarecategory');
        return $categories;
    }
    public function getSoftwareCategoryByName($name){
        $cat = R::findOne('softwarecategory', 'name = ?', [$name]);
        return $cat;
    }
    public function getSoftwareCategoryById($id){
        $cat = R::load('softwarecategory', $id);
        return $cat;
    }
    //Existing Item Actions
    public function createExistingItem(array $data){
        required = ['location', 'category', 'description', 'qty', 'lab', 'purchasedate' ];
        $item = R::dispense('existingitem');
        foreach ($required as $value){
            if (array_key_exists($value, $data))
            {
                $item[$value] = data[$value];
            }
            else
            {
                return -1;
            }
            if (array_key_exists('comments', $data))
            {
                $item['comments'] = $data['comments'];
            }
            else
            {
                $item['comments'] = '';
            }
            R::store($item);
        }
    }
}
