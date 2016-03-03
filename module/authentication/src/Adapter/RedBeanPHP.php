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

    private static $failMessage = 'Δεν ήταν δυνατή η σύνδεση. Παρακαλώ ελέγξτε το email και το συνθηματικό σας και δοκιμάστε ξανά.';
    /**
     * @var callable
     */
    protected $events;

    /**
     * @var callable
     */
    protected $resolveIdentityClass;

    /**
     * @var PasswordInterface
     */
    protected $crypt;

    public function __construct(callable $events, callable $resolveIdentityClass, PasswordInterface $crypt)
    {
        $this->events               = $events;
        $this->resolveIdentityClass = $resolveIdentityClass;
        $this->crypt                = $crypt;
    }

    public function authenticate()
    {
        $events = $this->events;
        $events('trigger', 'authenticate', $this);


        $email             = filter_var($this->getIdentity(), FILTER_VALIDATE_EMAIL);
        $isValidCredential = filter_var(strlen(trim($this->getCredential())), FILTER_VALIDATE_INT, [
            'options'=> ['min_range' => 8],
        ]);

        if (!$email || !$isValidCredential) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [self::$failMessage]);
        }

        $user = R::findOne('user', 'mail = ? AND authentication_source = ?', [$email, 'DB']);

        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, null, [self::$failMessage]);
        }

        if (!$this->crypt->verify($this->getCredential(), $user->password)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [self::$failMessage]);
        }

        $identityClass = call_user_func($this->resolveIdentityClass);
        $identity      = new $identityClass(
            $user->id,
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
