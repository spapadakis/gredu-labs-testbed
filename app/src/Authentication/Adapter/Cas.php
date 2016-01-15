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
use GrEduLabs\Authentication\Identity;
use phpCAS;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class Cas implements AdapterInterface
{
    public function __construct(array $settings = [])
    {
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
    }

    public function authenticate()
    {
        try {
            phpCAS::handleLogoutRequests();
            phpCAS::forceAuthentication();
            if (!phpCAS::isAuthenticated()) {
                return new Result(Result::FAILURE, null, ['Authentication failure']);
            }

            return new Result(
                Result::SUCCESS,
                self::identityFormCasAttributes(),
                ['Authentication success']
            );
        } catch (Exception $e) {
            return new Result(Result::FAILURE_UNCATEGORIZED, null, [$e->getMessage()]);
        }
    }

    private static function identityFormCasAttributes()
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

        return new Identity(
            $identity,
            $filterAttribute('mail'),
            $filterAttribute('cn'),
            $filterAttribute('ou')
        );
    }
}
