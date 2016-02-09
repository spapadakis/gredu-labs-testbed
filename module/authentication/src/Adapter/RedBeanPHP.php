<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authentication\Adapter;

use RedBeanPHP\R;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Zend\Crypt\Password\PasswordInterface;

class RedBeanPHP extends AbstractAdapter
{
    /**
     * @var string
     */

    private static $failMessage = 'Failed to login. Please check your email and password and try again';
    /**
     * @var callable
     */
    protected $events;

    /**
     * @var string
     */
    protected $identityClass;

    /**
     * @var PasswordInterface
     */
    protected $crypt;

    public function __construct(callable $events, $identityClass, PasswordInterface $crypt)
    {
        $this->events        = $events;
        $this->identityClass = (string) $identityClass;
        $this->crypt         = $crypt;
    }

    public function authenticate()
    {
        $events = $this->events;
        $events('trigger', 'authenticate', $this);

        $user = R::findOne('user', 'mail = ? AND authentication_source = ?', [
            $this->getIdentity(),
            'DB',
        ]);

        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, [self::$failMessage]);
        }

        if (!$this->crypt->verify($this->getCredential(), $user->password)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [self::$failMessage]);
        }

        $identityClass = $this->identityClass;
        $identity      = new $identityClass(
            $user->uid,
            $user->mail,
            $user->displayName,
            $user->officeName,
            'DB'
        );

        $events('trigger', 'authenticate.success', $identity);

        return new Result(Result::SUCCESS, $identity, ['Authentication success']);
    }
}
