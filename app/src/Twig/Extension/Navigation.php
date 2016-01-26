<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Twig\Extension;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouterInterface;
use Twig_Extension;
use Twig_SimpleFunction;
use Zend\Permissions\Acl\AclInterface;

class Navigation extends Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var array
     */
    private $navigation;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var AclInterface
     */
    private $acl;

    /**
     * @var string
     */
    private $currentRole;

    public function __construct(
        array $navigation,
        RouterInterface $router,
        ServerRequestInterface $request,
        AclInterface $acl = null,
        $currentRole = null
    ) {
        $this->navigation  = $navigation;
        $this->router      = $router;
        $this->request     = $request;
        $this->acl         = $acl;
        $this->currentRole = $currentRole;
    }

     /**
     * Extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'slim-navigation';
    }

    /**
     * Callback for twig.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('nav', [$this, 'nav']),
        ];
    }

    /**
     *
     */
    public function nav($root = null)
    {
        $navigation = (null !== $root) ? $this->navigation[$root] : $this->navigation;

        $aclFilter = function ($page) {
            if (!$this->acl) {
                return true;
            }

            $path = parse_url($page['href'], PHP_URL_PATH);

            $resource = 'route' . $path;

            return $this->acl->isAllowed($this->currentRole, $resource, 'get');
        };

        $prepare = function ($page) use (&$prepare, &$aclFilter) {

            if (isset($page['route'])) {
                $routeData    = isset($page['route_data']) ? $page['route_data'] : [];
                $query        = isset($page['query']) ? $page['query'] : [];
                $page['href'] = $this->router->pathFor($page['route'], $routeData, $query);
            }

            $path = parse_url($page['href'], PHP_URL_PATH);

            $page['active'] = $path === $this->request->getUri()->getPath();
            if (isset($page['pages']) && is_array($page['pages'])) {
                $page['pages'] = array_filter(array_map($prepare, $page['pages']), $aclFilter);
            }

            return $page;
        };

        return array_filter(array_map($prepare, $navigation), $aclFilter);
    }
}
