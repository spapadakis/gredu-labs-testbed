<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\OpenData\Action;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use GrEduLabs\OpenData\Service\DataProviderInterface;
use GrEduLabs\OpenData\Service\RedBeanQueryPagedDataProvider;

/**
 * Base class implementaing the api action. 
 * A dataprovider object is used to retrieve data.
 * 
 * @see GrEduLabs\OpenData\Service\DataProviderInterface
 */
class ApiAction
{

    /**
     * @var GrEduLabs\OpenData\Service\DataProviderInterface  
     */
    protected $dataProvider;

    /**
     * @var Slim\Container
     */
    protected $container;

    /**
     * @var boolean Respond with a 404 instead of 200 if data from dataprovider is an empty array.
     */
    protected $empty_data_404;

    public function __construct(Container $container, DataProviderInterface $dataProvider, $empty_data_404 = false)
    {
        $this->container = $container;
        $this->dataProvider = $dataProvider;
        $this->empty_data_404 = $empty_data_404;
    }

    public function setEmptyData404()
    {
        $this->empty_data_404 = true;
    }

    public function setEmptyData200()
    {
        $this->empty_data_404 = false;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        /**
         * Get data from dataprovider. 
         * If data is null, respond with a 500 status.
         * If data is empty, respond with a 404 when $empty_data_404 is true. 
         * In any other case respond with a 200 status. 
         * 
         * @see GrEduLabs\OpenData\Service\DataProviderInterface
         */
        $data = $this->dataProvider->getData();
        $status = 200;
        if ($data === null) {
            $status = 500;
            $data = ['message' => 'An error occured while retrieving data'];
        } elseif (!is_array($data)) {
            $status = 500;
            $data = ['message' => 'Unexpected data: ' . var_export($data, true)];
        } elseif (count($data) === 0) {
            $status = (($this->empty_data_404 === true) ? 404 : 200);
        }

        return $this->respond($res, 'JSON', $this->prepareResponseData($status, $data), $status);
    }

    /**
     * 
     * @param int $status HTTP status code
     * @param type $data the real data to wrap in response 
     * @return array An array with predetermined keys:
     * - status HTTP status code 
     * - success boolean 
     * - data The actual data 
     * - labels Data labels
     * - count Data count in resultset
     * - countall Data count without paging 
     * - page Data current page 
     * - pages Data number of pages available 
     */
    protected function prepareResponseData($status, $data = null)
    {
        $is_success = ($status == 200);
        $has_data = $is_success && isset($data);
        $has_paging = $this->dataProvider instanceof RedBeanQueryPagedDataProvider && $has_data;

        $response_data = [
            'status' => $status,
            'success' => $is_success,
            'count' => ($has_data ? $this->dataProvider->getCount() : null),
            'countall' => ($has_paging ? $this->dataProvider->getCountAll() : null),
            'page' => ($has_paging ? $this->dataProvider->getPage() : null),
            'pages' => ($has_paging ? $this->dataProvider->getPages() : null),
            'labels' => ($has_data ? $this->dataProvider->getLabels() : null),
            'data' => $data,
        ];

        return $response_data;
    }

    /**
     * Send the final response. Only Json format is currently supported. 
     * 
     * @param string $outputFormat i.e. JSON
     * @param array $response_data typically an array to send ot data to the client
     * @param int $status HTTP status code 
     * @return mixed response object
     */
    protected function respond(Response $res, $outputFormat, array $response_data, $status)
    {
        switch ($outputFormat) {
            case 'JSON':
            default:
                return $res->withJson($response_data, $status, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
}
