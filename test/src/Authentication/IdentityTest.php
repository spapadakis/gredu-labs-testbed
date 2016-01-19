<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Authentication;

use GrEduLabs\Authentication\Identity;

class IdentityTest extends \PHPUnit_Framework_TestCase
{
    private $identity;
    protected function setUp()
    {
        $this->identity = new Identity(
            'someUid',
            'some@mail.com',
            'Jonh Doe',
            'Office',
            'authSource'
        );
    }


    public function testConstructor()
    {
        $this->assertAttributeSame('someUid', 'uid', $this->identity);
        $this->assertAttributeSame('some@mail.com', 'mail', $this->identity);
        $this->assertAttributeSame('Jonh Doe', 'displayName', $this->identity);
        $this->assertAttributeSame('Office', 'officeName', $this->identity);
        $this->assertAttributeSame('authSource', 'authenticationSource', $this->identity);
    }

    public function testMagicGet()
    {
        $this->assertSame('someUid', $this->identity->uid);
        $this->assertSame('some@mail.com', $this->identity->mail);
        $this->assertSame('Jonh Doe', $this->identity->displayName);
        $this->assertSame('Office', $this->identity->officeName);
        $this->assertSame('authSource', $this->identity->authenticationSource);
    }

    public function testMagicGetReturnsNullIfNoProperty()
    {
        $this->assertNull($this->identity->test);
    }

    public function testToStringReturnsIdentityDisplayName()
    {
        $this->assertSame('Jonh Doe', $this->identity->__toString());
    }

    public function testGetUidReturnsIdentityUid()
    {
        $this->assertSame('someUid', $this->identity->getUid());
    }

    public function testToArray()
    {
        $this->assertEquals([
            'uid'                  => 'someUid',
            'mail'                 => 'some@mail.com',
            'displayName'          => 'Jonh Doe',
            'officeName'           => 'Office',
            'authenticationSource' => 'authSource',
        ], $this->identity->toArray());
    }

    public function testJsonSerializableIdentity()
    {
        $this->assertInstanceOf('\JsonSerializable', $this->identity);
        $this->assertJsonStringEqualsJsonString(
            json_encode($this->identity->toArray()),
            json_encode($this->identity)
        );
    }
}
