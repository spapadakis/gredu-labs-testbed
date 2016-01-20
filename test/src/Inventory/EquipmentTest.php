<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Inventory;

use GrEduLabs\Inventory\Equipment;

class EquipmentTest extends \PHPUnit_Framework_TestCase
{
    private $equipment;

    private $equipmentData = [
        'id'             => 82225,
        'category'       => 'RACK',
        'description'    => 'EASYNET 15U W/DOOR',
        'location'       => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡ/ΚΗΣ 1',
        'manufacturer'   => 'VERO ELEC',
        'propertyNumber' => 'ΚΤ-23243',
    ];

    protected function setup()
    {
        $this->equipment = new Equipment(
            $this->equipmentData['id'],
            $this->equipmentData['category'],
            $this->equipmentData['description'],
            $this->equipmentData['location'],
            $this->equipmentData['manufacturer'],
            $this->equipmentData['propertyNumber']
        );
    }

    public function testConstructorSetProperties()
    {
        $this->assertAttributeSame($this->equipmentData['id'], 'id', $this->equipment);
        $this->assertAttributeSame($this->equipmentData['category'], 'category', $this->equipment);
        $this->assertAttributeSame($this->equipmentData['description'], 'description', $this->equipment);
        $this->assertAttributeSame($this->equipmentData['location'], 'location', $this->equipment);
        $this->assertAttributeSame($this->equipmentData['manufacturer'], 'manufacturer', $this->equipment);
        $this->assertAttributeSame($this->equipmentData['propertyNumber'], 'propertyNumber', $this->equipment);
    }

    public function testMagicGetMethod()
    {
        $this->assertSame($this->equipmentData['id'], $this->equipment->id);
        $this->assertSame($this->equipmentData['category'], $this->equipment->category);
        $this->assertSame($this->equipmentData['description'], $this->equipment->description);
        $this->assertSame($this->equipmentData['location'], $this->equipment->location);
        $this->assertSame($this->equipmentData['manufacturer'], $this->equipment->manufacturer);
        $this->assertSame($this->equipmentData['propertyNumber'], $this->equipment->propertyNumber);
    }

    public function testMagicGetMethodReturnsNullIfNoPropertyExists()
    {
        $this->assertNull($this->equipment->someNotExistingProperty);
    }

    public function testToArrayMethod()
    {
        $this->assertEquals($this->equipmentData, $this->equipment->toArray());
    }
}
