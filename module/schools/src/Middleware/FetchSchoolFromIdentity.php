<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Middleware;

use RedBeanPHP\R;

class FetchSchoolFromIdentity
{
    protected $authService;

    public function __construct($authService)
    {
        $this->authService = $authService;
    }

    public function __invoke($req, $res, $next)
    {
        $identity = $this->authService->getIdentity();
        if (!$identity) {
            return $res->withStatus(403, 'No identity');
        }

        $user = R::load('user', $identity->id);
        if (!($school = $user->school)) {
            return $res->withStatus(403, 'No school');
        }

        return $next($req->withAttribute('school', (object) array_merge($school->export(), [
            'eduadmin' => $school->eduadmin->name,
            'regioneduadmin' => $school->eduadmin->regioneduadmin->name,
        ])), $res);
    }
}
