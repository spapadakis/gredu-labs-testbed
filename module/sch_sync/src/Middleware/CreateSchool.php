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

use GrEduLabs\Authentication\Identity;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use RedBeanPHP\R;
use Slim\Flash\Messages;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Ldap\Dn;
use Zend\Ldap\Filter;
use Zend\Ldap\Ldap;

class CreateSchool
{
    /**
     * @var Ldap
     */
    private $ldap;

    /**
     * @var callable
     */
    private $fetchUnit;

    /**
     * @var AuthenticationServiceInterface
     */
    private $authService;

    /**
     * @var string
     */
    private $unitNotFoundRedirectUrl;

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
        Ldap $ldap,
        callable $fetchUnitFromMM,
        AuthenticationServiceInterface $authService,
        $unitNotFoundRedirectUrl,
        $ssoLogoutUrl,
        Messages $flash,
        LoggerInterface $logger
    ) {
        $this->ldap                    = $ldap;
        $this->fetchUnit               = $fetchUnitFromMM;
        $this->authService             = $authService;
        $this->unitNotFoundRedirectUrl = (string) $unitNotFoundRedirectUrl;
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

        $registryNo = $this->findUnitRegitryNo($identity);
        if (null === $registryNo) {
            $this->logger->error(sprintf('Unit for user %s not found in LDAP', $identity->mail), $identity->toArray());

            return $this->logoutAndRediret($res, sprintf(
                'School not found.  <a href="%s" title="SSO logout">SSO Logout</a>',
                $this->ssoLogoutUrl
            ));
        }

        $unit = call_user_func($this->fetchUnit, $registryNo);
        if (null === $unit) {
            $this->logger->error(sprintf(
                'Unit with %s for user %s not found in MM',
                $identity->mail,
                $registryNo
            ));
            $this->logger->debug('Trace', ['registryNo'=> $registryNo, 'identity' => $identity->toArray()]);

            return $this->logoutAndRediret($res, sprintf(
                'School not found.  <a href="%s" title="SSO logout">SSO Logout</a>',
                $this->ssoLogoutUrl
            ));
        }

        $school = R::findOne('school', 'registry_no = ?', [$registryNo]);
        try {
            if (!$school) {
                R::begin();
                $school                     = R::dispense('school');
                $school->registry_no        = $unit['registry_no'];
                $school->name               = $unit['name'];
                $school->street_address     = $unit['street_address'];
                $school->postal_code        = $unit['postal_code'];
                $school->phone_number       = $unit['phone_number'];
                $school->fax_number         = $unit['fax_number'];
                $school->email              = $unit['email'];
                $school->municipality       = $unit['municipality'];
                $school->schooltype_id      = $unit['unit_type_id'];
                $school->prefecture_id      = $unit['prefecture_id'];
                $school->educationlevel_id  = $unit['education_level_id'];
                $school->eduadmin_id        = $unit['edu_admin_id'];
                $school->created            = time();
                $school->creator            = $identity->mail;
                $school_id                  = R::store($school);
                $this->logger->info(sprintf('School %s imported from MM to database', $registryNo), ['creator' => $identity->mail]);

                $user            = R::load('user', $identity->id);
                $user->school_id = $school_id;
                R::store($user);
                $this->logger->info(sprintf('Set school %s to user %s', $registryNo, $identity->mail));
                R::commit();
            }
        } catch (\Exception $e) {
            R::rollback();
            $this->logger->error(sprintf('Problem inserting school %s form MM in database', $registryNo));
            $this->logger->debug('Exception', [$e->getMessage(), $e->getTraceAsString()]);

            return $this->logoutAndRediret($res, sprintf(
                'A problem occured fetching school data. <a href="%s" title="SSO logout">SSO Logout</a>',
                $this->ssoLogoutUrl
            ));
        }

        return $res;
    }

    private function findUnitRegitryNo(Identity $identity)
    {
        $filter = Filter::equals('mail', $identity->mail);
        $baseDn = Dn::factory($this->ldap->getBaseDn())->prepend(['ou' => 'people']);
        $result = $this->ldap->search($filter, $baseDn, Ldap::SEARCH_SCOPE_ONE, ['l']);

        if (1 !== $result->count()) {
            return;
        }
        $result = $result->current();
        $unitDn = $result['l'][0];

        $unit = $this->ldap->getNode($unitDn);

        return $unit->getAttribute('gsnunitcode', 0);
    }

    private function logoutAndRediret(Response $res, $message)
    {
        $this->authService->clearIdentity();
        $this->flash->addMessage('danger', $message);

        return $res->withRedirect($this->unitNotFoundRedirectUrl);
    }
}
