<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSSO\Adapter;

use phpCAS;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class Cas implements AdapterInterface
{
    /**
     * @var callable
     */
    protected $initCas;

    /**
     * @var callable
     */
    protected $isAllowed;

    /**
     * @var callable
     */
    protected $events;

    /**
     * @var string
     */
    protected $identityClass;

    /**
     * @var string
     */
    protected $ssoLogoutUrl;


    public function __construct(
        callable $initCas,
        callable $isAllowed,
        callable $events,
        $identityClass,
        $ssoLogoutUrl
    ) {
        $this->initCas       = $initCas;
        $this->isAllowed     = $isAllowed;
        $this->events        = $events;
        $this->identityClass = (string) $identityClass;
        $this->ssoLogoutUrl  = (string) $ssoLogoutUrl;
    }

    public function authenticate()
    {
        $events = $this->events;
        $events('trigger', 'authenticate', $this);
        call_user_func($this->initCas);

        if (!phpCAS::forceAuthentication()) {
            return false;
        }

        $attributes = phpCAS::getAttributes();

        $isAllowed = call_user_func($this->isAllowed, $attributes);
        if (!$isAllowed) {
            return new Result(Result::FAILURE, null, [sprintf(
                'Your account type is not accepted. <a href="%s" title="SSO logout">SSO Logout</a>',
                $this->ssoLogoutUrl
            )]);
        }

        $identity = phpCAS::getUser();

        $filterAttribute = function ($attribute) use ($attributes) {
            if (!isset($attributes[$attribute])) {
                return;
            }

            if (is_array($attributes[$attribute])) {
                return $attributes[$attribute];
            }

            return $attributes[$attribute];
        };

        $identityClass = $this->identityClass;
        $identity      = new $identityClass(
            $identity,
            $filterAttribute('mail'),
            $filterAttribute('cn'),
            $filterAttribute('ou'),
            'CAS'
        );

        $events('trigger', 'authenticate.success', $identity);

        return new Result(Result::SUCCESS, $identity, ['Authentication success']);
    }
}
