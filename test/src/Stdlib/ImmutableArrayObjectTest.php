<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Stdlib;

use GrEduLabs\Stdlib\ImmutableArrayObject;

class ImmutableArrayObjectTest extends \PHPUnit_Framework_TestCase
{
    protected $arrayObject;

    protected function setup()
    {
        $this->arrayObject = new ImmutableArrayObject([
            'five',
        ]);
    }

    public function testAppendThrowsException()
    {
        $this->setExpectedException('LogicException', 'Attempting to write to an immutable array');
        $this->arrayObject->append('one');
    }

    public function testExchangeArrayThrowsException()
    {
        $this->setExpectedException('LogicException', 'Attempting to write to an immutable array');
        $this->arrayObject->exchangeArray(['one', 'two', 'three']);
    }

    public function testOffsetSetThrowsException()
    {
        $this->setExpectedException('LogicException', 'Attempting to write to an immutable array');
        $this->arrayObject->offsetSet(0, 'four');
    }

    public function testOffsetUnsetThrowsException()
    {
        $this->setExpectedException('LogicException', 'Attempting to write to an immutable array');
        $this->arrayObject->offsetUnset(0);
    }
}
