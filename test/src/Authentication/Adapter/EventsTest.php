<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Authentication\Adapter;

use GrEduLabs\Authentication\Adapter\Events;
use Zend\Authentication\Result;
use Zend\EventManager\EventManager;

class EventsTest extends \PHPUnit_Framework_TestCase
{
    private $adapter;

    protected function setUp()
    {
        $this->adapter = new Events();
    }

    public function testConstructorSetsEventManager()
    {
        $events  = new EventManager();
        $adapter = new Events($events);
        $this->assertAttributeSame($events, 'events', $adapter);
    }

    public function testGetEventManagerReturnsDefaultEventManager()
    {
        $adapter = new Events();
        $events  = $adapter->getEventManager();
        $this->assertInstanceOf('\Zend\EventManager\EventManagerInterface', $events);
    }

    public function testIdentifiersSet()
    {
        $events = new EventManager();
        $this->assertEmpty($events->getIdentifiers());
        $adapter = new Events($events);
        $this->assertNotEmpty($events->getIdentifiers());
    }

    public function testAuthenticateTriggerEvent()
    {
        $triggered = false;
        $events    = $this->adapter->getEventManager();
        $events->attach(Events::EVENT_AUTH, function ($event) use (&$triggered) {
            $triggered = true;
        });
        $result = $this->adapter->authenticate();
        $this->assertTrue($triggered);
        $this->assertInstanceOf('Zend\Authentication\Result', $result);
    }

    public function testAuthenticateReturnsFirstTruthyListener()
    {
        $events = $this->adapter->getEventManager();
        $events->attach(Events::EVENT_AUTH, function ($event) {
            return 'test';
        }, 10);
        $events->attach(Events::EVENT_AUTH, function ($event) {
            return 'ok';
        }, 15);
        $events->attach(Events::EVENT_AUTH, function ($event) {
            return false;
        }, 20);
        $events->attach(Events::EVENT_AUTH, function ($event) {
            return;
        }, 5);

        $result = $this->adapter->authenticate();
        $this->assertTrue($result->isValid());
        $this->assertSame('ok', $result->getIdentity());
    }

    public function authenticateResults()
    {
        return [
            [false, [false, null, Result::FAILURE]],
            ['identity', [true, 'identity', Result::SUCCESS]],
        ];
    }

    /**
     * @dataProvider authenticateResults
     */
    public function testAuthenticateFailedResult($listenerReturn, $expectedResult)
    {
        $events = $this->adapter->getEventManager();
        $events->attach(Events::EVENT_AUTH, function () use ($listenerReturn) {
            return $listenerReturn;
        });

        $result = $this->adapter->authenticate();
        $this->assertSame($expectedResult[0], $result->isValid());
        $this->assertSame($expectedResult[1], $result->getIdentity());
        $this->assertSame($expectedResult[2], $result->getCode());
    }

    public function testAuthenticatePassIdentityAndCrendentialToListener()
    {
        $identity   = null;
        $credential = null;
        $events     = $this->adapter->getEventManager();
        $events->attach(Events::EVENT_AUTH, function ($event) use (&$identity, &$credential) {
            $identity = $event->getParam('identity');
            $credential = $event->getParam('credential');
        });
        $this->adapter->setIdentity('username');
        $this->adapter->setCredential('password');
        $this->adapter->authenticate();
        $this->assertSame('username', $identity);
        $this->assertSame('password', $credential);
    }

    public function testAuthenticateIfListenerThrowsException()
    {
        $events = $this->adapter->getEventManager();
        $events->attach(Events::EVENT_AUTH, function () {
            throw new \Exception('test');
        });
        $result = $this->adapter->authenticate();
        $this->assertFalse($result->isValid());
        $this->assertSame(Result::FAILURE_UNCATEGORIZED, $result->getCode());
        $this->assertSame(['test'], $result->getMessages());
        $this->assertNull($result->getIdentity());
    }
}
