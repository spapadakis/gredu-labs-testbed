<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSSO\Listener;

use Psr\Log\LoggerInterface;
use RedBeanPHP\R;

class User
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(callable $stop, $identity)
    {
        $user = R::findOne('user', 'mail = ?', [$identity->mail]);
        if (!$user) {
            $user                        = R::dispense('user');
            $user->uid                   = $identity->uid;
            $user->mail                  = $identity->mail;
            $user->display_name          = $identity->displayName;
            $user->office_name           = $identity->officeName;
            $user->authentication_source = $identity->authenticationSource;
            $user->password              = '';
            $user->created               = time();
            $user->role                  = 'school';
            $this->logger->info(sprintf('User %s imported from sso.sch.gr to database', $identity->mail));
        }
        $user->last_login = time();
        R::store($user);
    }
}
