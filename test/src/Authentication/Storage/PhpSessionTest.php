<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Authentication\Storage;

use GrEduLabs\Authentication\Storage\PhpSession;

class PhpSessionTest extends \PHPUnit_Framework_TestCase
{
    private $storage;

    public static function setUpBeforeClass()
    {
        @session_start();
    }
    public static function tearDownAfterClass()
    {
        @session_destroy();
    }

    protected function setUp()
    {
        $this->storage = new PhpSession();
    }

    protected function tearDown()
    {
        $_SESSION = [];
    }

    public function testSetingNamespaceAndMember()
    {
        $storage = new PhpSession('TEST_NS', 'TEST_MEMBER');
        $this->assertAttributeSame('TEST_NS', 'namespace', $storage);
        $this->assertAttributeSame('TEST_MEMBER', 'member', $storage);
    }

    public function testGetNamesapceMethod()
    {
        $storage = new PhpSession('TEST_NS');
        $this->assertSame('TEST_NS', $storage->getNamespace());
    }

    public function testGetMemberMethod()
    {
        $storage = new PhpSession('TEST_NS', 'TEST_MEMBER');
        $this->assertSame('TEST_MEMBER', $storage->getMember());
    }

    public function testIsEmptyMethodWhenSessionIsEmpty()
    {
        $this->assertTrue($this->storage->isEmpty());
    }

    public function testIsEmptyMethodWhenSessionNotEmpty()
    {
        $_SESSION[PhpSession::NAMESPAGE_DEFAULT][PhpSession::MEMBER_DEFAULT] = 'test';
        $this->assertFalse($this->storage->isEmpty());
    }

    public function testReadMethodReturnCorrectResult()
    {
        $_SESSION[PhpSession::NAMESPAGE_DEFAULT][PhpSession::MEMBER_DEFAULT] = 'test';
        $this->assertSame('test', $this->storage->read());
    }

    public function testWriteMethodSetsContents()
    {
        $this->storage->write('test');
        $this->assertSame('test', $_SESSION[PhpSession::NAMESPAGE_DEFAULT][PhpSession::MEMBER_DEFAULT]);
    }

    public function testClearMethodUnsetSession()
    {
        $this->storage->write('test');
        $this->storage->clear();
        $this->assertFalse(array_key_exists(PhpSession::MEMBER_DEFAULT, $_SESSION[PhpSession::NAMESPAGE_DEFAULT]));
    }
}
