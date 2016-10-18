<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
namespace GrEduLabs\OpenData\Service;

use Slim\Router;

/**
 *
 * 
 */
class IndexProvider implements DataProviderInterface
{

    /**
     * @var string uri for the documentation 
     */
    protected $api_doc_url;

    /**
     * @var 
     */
    protected $router;

    /**
     * @var array The data exposed from the index page
     */
    protected $data;

    public function __construct($api_doc_url, Router $router)
    {
        $this->api_doc_url = $api_doc_url;

        /**
         * TODO This is just a demo; to be removed shortly 
         */
        $routes = array_reduce($router->getRoutes(), function ($routes, $route) {
            $pattern = $route->getPattern();
            if (strpos($pattern, '/open-data/api') !== false) {
                $path_parts = explode("/", $pattern);
                $routes[] = [
                    'label' => end($path_parts),
                    'path' => $pattern,
                ];
            }
            return $routes;
        }, []);
        $this->data = $routes;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getCount()
    {
        return count($this->data);
    }

    /**
     * @inheritdoc
     */
    public function getLabels()
    {
        return [
            'label' => 'Ονομασία',
            'path' => 'Σχετικό path για κλήση',
        ];
    }
}
