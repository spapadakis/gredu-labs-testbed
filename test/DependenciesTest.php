<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

class DependenciesTest extends \PHPUnit_Framework_TestCase
{
    private static $container;
    private static $settings = [
        'settings' => [
            'view' => [
                'template_path' => __DIR__,
                'twig'          => [
                    'cache'       => __DIR__,
                    'debug'       => true,
                    'auto_reload' => true,
                ],
            ],
            'logger' => [
                'name' => 'app',
                'path' => __DIR__ . '/tmp/app.log',
            ],
            'db'                                => [
                'dsn'     => 'sqlite:' . __DIR__ . '/tmp/db.sq3',
                'user'    => null,
                'pass'    => null,
                'options' => [

                ],
            ],
        ],
    ];

    public static function setUpBeforeClass()
    {
        @session_start();
        $app = new \Slim\App(self::$settings);
        require __DIR__ . '/../app/dependencies.php';
        self::$container = $container;
        @session_destroy();
    }

    public function testView()
    {
        $view = self::$container->get('view');
        $this->assertInstanceOf('\Slim\Views\Twig', $view);
    }

    public function testFlash()
    {
        $flash = self::$container->get('flash');
        $this->assertInstanceOf('\Slim\Flash\Messages', $flash);
    }

    public function testLogger()
    {
        $logger = self::$container->get('logger');
        $this->assertInstanceOf('\Monolog\Logger', $logger);
    }

    public function testEvents()
    {
        $events = self::$container->get('events');
        $this->assertInstanceOf('\Zend\EventManager\EventManagerInterface', $events);
    }

    public function testDbAuthAdapter()
    {
        $adapter = self::$container->get('Service\\Authentication\\DbAdapter');
        $this->assertInstanceOf('\GrEduLabs\Authentication\Adapter\Pdo', $adapter);
    }

    public function testAuthStorage()
    {
        $storage = self::$container->get('Service\\Authentication\\Storage');
        $this->assertInstanceOf('\Zend\Authentication\Storage\StorageInterface', $storage);
    }

    public function testAuthService()
    {
        $service = self::$container->get('Service\\Authentication');
        $this->assertInstanceOf('\Zend\Authentication\AuthenticationService', $service);
    }
}
