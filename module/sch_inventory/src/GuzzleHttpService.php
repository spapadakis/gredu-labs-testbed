<?php
/**
 * gredu_labs
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace  SchInventory;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\UriInterface;

/**
 * Inventory service implementation using GuzzleHttp library
 */
class GuzzleHttpService implements ServiceInterface
{
    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * Class constructor
     *
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Retrieves all equipment data for unit
     *
     * @param mixed $unit
     * @return EquipmentCollection
     */
    public function getUnitEquipment($unit)
    {
        $response = $this->httpClient->request('GET', $this->createBaseUri($unit));

        $responseData = json_decode($response->getBody()->getContents(), true);

        return isset($responseData['flat_results']) ? $responseData['flat_results'] : [];
    }

    /**
     * Creates the uri with the unit query parameter
     *
     * @param mixed $unit
     * @return UriInterface
     */
    private function createBaseUri($unit)
    {
        $config  = $this->httpClient->getConfig();
        $baseUri = $config['base_uri'];

        return $baseUri->withQueryValue($baseUri, 'unit', $unit);
    }
}
