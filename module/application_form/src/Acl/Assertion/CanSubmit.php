<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ApplicationForm\Acl\Assertion;

use GrEduLabs\ApplicationForm\Service\ApplicationFormServiceInterface;
use RedBeanPHP\R;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

class CanSubmit implements AssertionInterface
{
    /**
     *
     * @var AuthenticationServiceInterface
     */
    protected $authService;
    /**
     *
     * @var ApplicationFormServiceInterface
     */
    protected $appFormService;

    public function __construct(
        AuthenticationServiceInterface $authService,
        ApplicationFormServiceInterface $appFormService
    ) {
        $this->authService    = $authService;
        $this->appFormService = $appFormService;
    }

    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        $identity = $this->authService->getIdentity();
        $user     = R::load('user', $identity->id);
        if (!($school = $user->school)) {
            return false;
        }
        $appForm = $this->appFormService->findSchoolApplicationForm($school->id);

        return null === $appForm;
    }
}
