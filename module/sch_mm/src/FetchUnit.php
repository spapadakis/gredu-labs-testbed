<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchMM;

use GuzzleHttp\ClientInterface;

class FetchUnit
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

    public function __invoke($mmId)
    {
        $config = $this->httpClient->getConfig();
        $baseUri = $config['base_uri'];
        $auth = $config['auth'];
        $url = $baseUri->withQueryValue($baseUri, 'registry_no', $mmId);
        $response = $this->httpClient->request('GET', $url, ['auth' => $auth]);
       
        $responseData = json_decode($response->getBody()->getContents(), true);
        if (!isset($responseData['data']) || empty($responseData['data'])) {
            return null;
        }
        return $responseData['data'][0];
    }
}