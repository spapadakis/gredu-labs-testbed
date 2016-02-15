<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace  SchInventory;

class Equipment
{
    protected $id;

    protected $category;

    protected $description;

    protected $location;

    protected $manufacturer;

    protected $propertyNumber;

    public function __construct($id, $category, $description, $location, $manufacturer, $propertyNumber)
    {
        $this->id             = $id;
        $this->category       = $category;
        $this->description    = $description;
        $this->location       = $location;
        $this->manufacturer   = $manufacturer;
        $this->propertyNumber = $propertyNumber;
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return;
    }

    public function toArray()
    {
        return [
            'id'             => $this->id,
            'category'       => $this->category,
            'description'    => $this->description,
            'location'       => $this->location,
            'manufacturer'   => $this->manufacturer,
            'propertyNumber' => $this->propertyNumber,
        ];
    }
}
