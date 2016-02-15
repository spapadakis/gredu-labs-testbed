<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSync\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use RedBeanPHP\R;
use Slim\Flash\Messages;
use Zend\Authentication\AuthenticationService;

class CreateUser
{
    /**
     * @var AuthenticationService
     */
    private $authService;

    /**
     * @var string
     */
    private $userErrorRedirectUrl;

    /**
     * @var string
     */
    private $ssoLogoutUrl;

    /**
     * @var Messages
     */
    private $flash;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        AuthenticationService $authService,
        $userErrorRedirectUrl,
        $ssoLogoutUrl,
        Messages $flash,
        LoggerInterface $logger
    ) {
        $this->authService             = $authService;
        $this->userErrorRedirectUrl    = (string) $userErrorRedirectUrl;
        $this->ssoLogoutUrl            = (string) $ssoLogoutUrl;
        $this->flash                   = $flash;
        $this->logger                  = $logger;
    }

    public function __invoke(Request $req, Response $res, callable $next)
    {
        $res = $next($req, $res);

        $identity = $this->authService->getIdentity();
        if (!$identity) {
            return $res;
        }
        try {
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
                $this->logger->info(sprintf(
                    'User %s imported from sso.sch.gr to database',
                    $identity->mail
                ));
            }
            $user->last_login = time();
            $user_id          = R::store($user);

            $identityClass = get_class($identity);
            $newIdentity   = new $identityClass(
                $user_id,
                $user->uid,
                $user->mail,
                $user->display_name,
                $user->office_name,
                $user->authentication_source
            );
            $this->authService->getStorage()->write($newIdentity);
        } catch (\Exception $e) {
            $this->authService->clearIdentity();
            $this->flash->addMessage(
                'danger',
                'A problem occured storing user in database. <a href="%s" title="SSO logout">SSO Logout</a>'
            );
            $this->logger->error('Problem inserting user form CAS in database', $identity->toArray());
            $this->logger->debug('Exception', [$e->getMessage(), $e->getTraceAsString()]);

            return $res->withRedirect($this->userErrorRedirectUrl);
        }

        return $res;
    }
}
