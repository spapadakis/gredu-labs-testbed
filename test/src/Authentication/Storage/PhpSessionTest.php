<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabstest\Authentication\Storage;

use GrEduLabs\Authentication\Storage\PhpSession;

class PhpSessionTest extends \PHPUnit_Framework_TestCase
{
    private $storage;

    private $session;

    protected function setUp()
    {
        $this->session = [];
        $this->storage = new PhpSession($this->session);
    }

    public function testPassSessionArrayByRefernce()
    {
        $this->assertTrue(is_array($this->session[PhpSession::NAMESPAGE_DEFAULT]));
    }

    public function testSetingNamespaceAndMember()
    {
        $session = [];
        $storage = new PhpSession($session, 'TEST_NS', 'TEST_MEMBER');
        $this->assertAttributeSame('TEST_NS', 'namespace', $storage);
        $this->assertAttributeSame('TEST_MEMBER', 'member', $storage);
    }

    public function testGetNamesapceMethod()
    {
        $session = [];
        $storage = new PhpSession($session, 'TEST_NS');
        $this->assertSame('TEST_NS', $storage->getNamespace());
    }

    public function testGetMemberMethod()
    {
        $session = [];
        $storage = new PhpSession($session, 'TEST_NS', 'TEST_MEMBER');
        $this->assertSame('TEST_MEMBER', $storage->getMember());
    }

    public function testIsEmptyMethodWhenSessionIsEmpty()
    {
        $this->assertTrue($this->storage->isEmpty());
    }

    public function testIsEmptyMethodWhenSessionNotEmpty()
    {
        $this->session[PhpSession::NAMESPAGE_DEFAULT][PhpSession::MEMBER_DEFAULT] = 'test';
        $this->assertFalse($this->storage->isEmpty());
    }

    public function testReadMethodReturnCorrectResult()
    {
        $this->session[PhpSession::NAMESPAGE_DEFAULT][PhpSession::MEMBER_DEFAULT] = 'test';
        $this->assertSame('test', $this->storage->read());
    }

    public function testWriteMethodSetsContents()
    {
        $this->storage->write('test');
        $this->assertSame('test', $this->session[PhpSession::NAMESPAGE_DEFAULT][PhpSession::MEMBER_DEFAULT]);
    }

    public function testClearMethodUnsetSession()
    {
        $this->storage->write('test');
        $this->storage->clear();
        $this->assertFalse(array_key_exists(PhpSession::MEMBER_DEFAULT, $this->session[PhpSession::NAMESPAGE_DEFAULT]));
    }
}
