<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Twig\Extension;

use GrEduLabs\Twig\Extension\Flash;

class FlashTest extends \PHPUnit_Framework_TestCase
{

    private $flash;

    private $mockMessages;

    private $template;

    protected function setUp()
    {
        $this->mockMessages = $this->getMock('\\Slim\\Flash\\Messages');
        $this->mockMessages->expects($this->any())
            ->method('getMessages')
            ->will($this->returnValue([
                'first' => ['test message 1'],
                'other' => [
                    'test message 2',
                    'test message 3',
                ],
            ]));
        $this->template = 'flashtemplate.twig';

        $this->flash = new Flash($this->mockMessages, $this->template);
    }

    public function testConstructorSetArgs()
    {
        $this->assertAttributeSame($this->mockMessages, 'flash', $this->flash);
        $this->assertAttributeSame($this->template, 'template', $this->flash);
    }

    public function testGetName()
    {
        $this->assertSame('slim-flash', $this->flash->getName());
    }

    public function testInitRuntimeSetEnvironment()
    {
        $env = $this->getMock('\\Twig_Environment');
        $this->flash->initRuntime($env);
        $this->assertAttributeSame($env, 'environment', $this->flash);
    }

    public function testGetFunctionsReturnsArrayWithSimpleFunction()
    {
        $functions = $this->flash->getFunctions();
        $this->assertInternalType('array', $functions);
        $this->assertNotEmpty($functions);
        $theFunction = reset($functions);
        $this->assertInstanceOf('\\Twig_SimpleFunction', $theFunction);
        $this->assertSame('flash', $theFunction->getName());
        $this->assertTrue($theFunction->needsEnvironment());
        $theFunctionRefl = new \ReflectionClass($theFunction);
        $options         = $theFunctionRefl->getProperty('options');
        $options->setAccessible(true);
        $options = $options->getValue($theFunction);
        $this->assertSame(['all'], $options['is_safe']);
    }

    public function testMessagesReturnsStringWithMessages()
    {
        $env = new \Twig_Environment(
            new \Twig_Loader_Filesystem(__DIR__)
        );
        $this->flash->initRuntime($env);
        $response = $this->flash->messages();
        $expected = <<< EOF
first: test message 1
other: test message 2
other: test message 3
EOF;
        $this->assertContains($expected, $response);
    }
}
