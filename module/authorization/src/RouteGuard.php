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
use Zend\Permissions\Acl\AclInterface;

class RouteGuard
{
    /**
     * @param AclInterface $acl             The preconfigured ACL service
     * @param string       $currentUserRole
     */
    public function __construct(AclInterface $acl, $currentUserRole)
    {
        $this->acl             = $acl;
        $this->currentUserRole = $currentUserRole;
    }

    /**
     * Invoke middleware.
     *
     * @param RequestInterface  $request  PSR7 request object
     * @param ResponseInterface $response PSR7 response object
     * @param callable          $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (!$request->getAttribute('route')) {
            return $response->withStatus(404);
        }
        $isAllowed = false;
        if ($this->acl->hasResource('route' . $request->getAttribute('route')->getPattern())) {
            $isAllowed = $isAllowed || $this->acl->isAllowed($this->currentUserRole, 'route' . $request->getAttribute('route')->getPattern(), strtolower($request->getMethod()));
        }

        if (is_string($request->getAttribute('route')->getCallable()) &&
            $this->acl->hasResource('callable/' . $request->getAttribute('route')->getCallable())) {
            $isAllowed = $isAllowed || $this->acl->isAllowed($this->currentUserRole, 'callable/' . $request->getAttribute('route')->getCallable());
        }

        if (!$isAllowed) {
            return $response->withStatus(403, $this->currentUserRole . ' is not allowed access to this location.');
        }

        return $next($request, $response);
    }
}
