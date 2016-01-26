<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication\Adapter;

use Exception;
use phpCAS;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class Cas implements AdapterInterface
{
    use IdentityPrototypeCapableTrait;

    /**
     * @var bool
     */
    protected $__init__ = false;

    /**
     * @var array
     */
    protected $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function authenticate()
    {
        try {
            $this->init();
            phpCAS::handleLogoutRequests();
            phpCAS::forceAuthentication();
            if (!phpCAS::isAuthenticated()) {
                return new Result(Result::FAILURE, null, ['Authentication failure']);
            }

            return new Result(
                Result::SUCCESS,
                $this->identityFormCasAttributes(),
                ['Authentication success']
            );
        } catch (Exception $e) {
            return new Result(Result::FAILURE_UNCATEGORIZED, null, [$e->getMessage()]);
        }
    }

    public function logout($redirect = null)
    {
        $this->init();
        if (!phpCAS::isAuthenticated()) {
            return;
        }

        if ($redirect) {
            phpCAS::logoutWithRedirectService((string) $redirect);
        }
        phpCAS::logout();
    }

    private function identityFormCasAttributes()
    {
        $attributes = phpCAS::getAttributes();
        $identity   = phpCAS::getUser();

        $filterAttribute = function ($attribute) use ($attributes) {
            if (!isset($attributes[$attribute])) {
                return;
            }

            if (is_array($attributes[$attribute])) {
                return $attributes[$attribute];
            }

            return $attributes[$attribute];
        };
        $identityClass = $this->identityPrototype;

        return new $identityClass(
            $identity,
            $filterAttribute('mail'),
            $filterAttribute('cn'),
            $filterAttribute('ou'),
            'CAS'
        );
    }

    private function init()
    {
        if (!$this->__init__) {
            $settings = $this->settings;
            phpCAS::client(
                $settings['serverVersion'],
                $settings['serverHostname'],
                $settings['serverPort'],
                $settings['serverUri'],
                $settings['changeSessionId']
            );

            if (($casServerCaCert = $settings['casServerCaCert'])) {
                if ($settings['casServerCnValidate']) {
                    phpCAS::setCasServerCACert($casServerCaCert, true);
                } else {
                    phpCAS::setCasServerCACert($casServerCaCert, false);
                }
            }

            if ($settings['noCasServerValidation']) {
                phpCAS::setNoCasServerValidation();
            }
            $this->__init__ = true;
        }
    }
}
