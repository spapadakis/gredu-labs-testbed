<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authorization;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Zend\Permissions\Acl\AclInterface;

class RouteGuard
{
    /**
     *
     * @var AclInterface
     */
    private $acl;

    /**
     *
     * @var string
     */
    private $currentUserRole;

    /**
     *
     * @var string
     */
    private $defaultRole;

    /**
     *
     * @var string
     */
    private $loginUrl;

    /**
     * @param AclInterface $acl             The preconfigured ACL service
     * @param string       $currentUserRole
     */
    public function __construct(AclInterface $acl, $currentUserRole, $defaultRole, $loginUrl)
    {
        $this->acl             = $acl;
        $this->currentUserRole = $currentUserRole;
        $this->defaultRole     = $defaultRole;
        $this->loginUrl        = $loginUrl;
    }

    /**
     * Invoke middleware.
     *
     * @param RequestInterface  $req  PSR7 request object
     * @param ResponseInterface $res PSR7 response object
     * @param callable          $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(Request $req, Response $res, callable $next)
    {
        if (!$req->getAttribute('route')) {
            return $res->withStatus(404);
        }
        $isAllowed = false;
        if ($this->acl->hasResource('route' . $req->getAttribute('route')->getPattern())) {
            $isAllowed = $isAllowed || $this->acl->isAllowed($this->currentUserRole, 'route' . $req->getAttribute('route')->getPattern(), strtolower($req->getMethod()));
        }

        if (is_string($req->getAttribute('route')->getCallable()) &&
            $this->acl->hasResource('callable/' . $req->getAttribute('route')->getCallable())) {
            $isAllowed = $isAllowed || $this->acl->isAllowed($this->currentUserRole, 'callable/' . $req->getAttribute('route')->getCallable());
        }

        if (!$isAllowed && $this->currentUserRole === $this->defaultRole) {
            return $res->withRedirect($this->loginUrl);
        }

        if (!$isAllowed) {
            $res = $res->withStatus(403, $this->currentUserRole . ' is not allowed access to this location.');
            $res->getBody()->write('Forbidden');

            return $res;
        }

        return $next($req, $res);
    }
}
