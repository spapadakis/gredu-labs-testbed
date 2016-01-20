<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabsTest\Inventory;

use GrEduLabs\Inventory\GuzzleHttpService;
use GuzzleHttp\Psr7\Uri;

class GuzzleHttpServiceTest extends \PHPUnit_Framework_TestCase
{
    private $guzzleClient;

    private $service;

    protected function setup()
    {
        $responseBody = $this->getMock('Psr\\Http\\Message\\StreamInterface');
        $responseBody->expects($this->any())
            ->method('getContents')
            ->will($this->returnValue(
                file_get_contents(__DIR__ . '/dummy-response.json')
            ));

        $response = $this->getMock('Psr\\Http\\Message\\ResponseInterface');
        $response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue($responseBody));

        $uri = new Uri();

        $this->guzzleClient = $this->getMock('GuzzleHttp\\ClientInterface');
        $this->guzzleClient->expects($this->any())
            ->method('request')
            ->will($this->returnValue($response));

        $this->guzzleClient->expects($this->any())
            ->method('getConfig')
            ->will($this->returnValue([
                'base_uri' => $uri,
            ]));

        $this->service = new GuzzleHttpService($this->guzzleClient);
    }

    public function testGetUnitEquipmentReturnsCollection()
    {
        $response = $this->service->getUnitEquipment('0123456');
        $this->assertInstanceof('\\GrEduLabs\\Inventory\\EquipmentCollection', $response);
    }
}
