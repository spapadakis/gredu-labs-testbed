<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSync\Action;

use RedBeanPHP\R;
use Slim\Http\Request;
use Slim\Http\Response;
use Zend\Authentication\AuthenticationServiceInterface;

class Sync
{
    /**
     *
     * @var AuthenticationServiceInterface
     */
    protected $authService;

    /**
     *
     * @var callable
     */
    protected $syncFromInventory;

    public function __construct(
        callable $syncFromInventory,
        AuthenticationServiceInterface $authService
    ) {
        $this->syncFromInventory = $syncFromInventory;
        $this->authService       = $authService;
    }

    public function __invoke(Request $req, Response $res)
    {
        $identity = $this->authService->getIdentity();
        if (null === $identity) {
            return $res;
        }
        $user = R::load('user', $identity->id);
        if (!$user->school_id) {
            return $res;
        }

        $school_id = $user->school_id;
        $sync      = $this->syncFromInventory;
        $result    = $sync($school_id);
        if (false === $result) {
            return $res->withStatus(500);
        }

        return $res->withJson($result);
    }
}
