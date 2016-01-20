<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Inventory;

use ArrayObject;
use GrEduLabs\Inventory\Equipment;
use GrEduLabs\Inventory\EquipmentCollection;

class EquipmentCollectionTest extends \PHPUnit_Framework_TestCase
{
    private $collection;

    private $collectionData;

    protected function setup()
    {
        $this->collectionData = [
            new Equipment(
                82225,
                'RACK',
                'EASYNET 15U W/DOOR',
                'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡ/ΚΗΣ 1',
                'VERO ELEC',
                'ΚΤ-23243'
            ),
             new Equipment(
                82182,
                'MODEM / ROUTER ',
                'CISCO 876',
                'ΑΠΟΘΗΚΗ',
                'CISCO SYSTEMS',
                'ΚΤ-23203'
            ),
             new Equipment(
                98487,
                'ΟΘΟΝΗ',
                'SUN MICROSYSTEMS INC X7202A',
                'ΔΙΟΙΚΗΣΗ',
                'SUN MICROSYSTEMS INC.',
                'ΚΤ-27160'
            ),
             new Equipment(
                98787,
                'WORKSTATION',
                'GENERIC WORKSTATION',
                'ΑΠΟΘΗΚΗ',
                'GENERIC',
                'ΚΤ-27172'
            ),
        ];
        $this->collection = new EquipmentCollection($this->collectionData);
    }

    public function testConstructorThrowsIfArgNotContainsEquipmentObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        new EquipmentCollection(['test', [], new \stdClass()]);
    }

    public function testConsturctorAcceptsTraversable()
    {
        $collection = new EquipmentCollection(new ArrayObject($this->collectionData));
        $this->assertEquals($collection, $this->collection);
    }

    public function testWithLocationMethodReturnsANewCollection()
    {
        $collection = $this->collection->withLocation('ΔΙΟΙΚΗΣΗ');
        $this->assertNotSame($this->collection, $collection);
    }

    public function testWithLocationMethodReturnsACollectionWithGivenLocation()
    {
        $collection = $this->collection->withLocation('ΔΙΟΙΚΗΣΗ');
        $data       = array_values($collection->getArrayCopy());
        $this->assertEquals($data, [$this->collectionData[2]]);
    }

    public function testWithCategoryMethodReturnsANewCollection()
    {
        $collection = $this->collection->withCategory('WORKSTATION');
        $this->assertNotSame($this->collection, $collection);
    }

    public function testWithCategoryMethodReturnsACollectionWithGivenCategory()
    {
        $collection = $this->collection->withCategory('WORKSTATION');
        $data       = array_values($collection->getArrayCopy());
        $this->assertEquals($data, [$this->collectionData[3]]);
    }
}
