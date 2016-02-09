<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Authorization;

use Exception;
use Interop\Container\ContainerInterface;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Assertion\CallbackAssertion;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Role\GenericRole as Role;

class Acl extends ZendAcl
{
    public function __construct(array $configuration, ContainerInterface $container = null)
    {
        // setup $roles
        foreach ($configuration['roles'] as $role => $parents) {
            $this->addRole(new Role($role), $parents);
        }

        // setup resources
        if (array_key_exists('resources', $configuration)) {
            foreach (array_unique($configuration['resources']) as $resource => $parent) {
                $this->addResource(new Resource($resource), $parent);
            }
        }

        if (array_key_exists('guards', $configuration)) {
            foreach ($configuration['guards'] as $guardType => $guardRules) {
                foreach ($guardRules as $rule) {
                    if (($ruleLength = count($rule)) < 2) {
                        throw new Exception('Error Processing Request');
                    }

                    list($resource, $roles, $privileges, $assertion) = array_merge(
                        $rule, array_fill(0, 4, null)
                    );

                    switch ($guardType) {
                        case 'resources':
                            break;
                        case 'routes':
                            if ($ruleLength < 3) {
                                throw new Exception('Error Processing Request');
                            }
                            $resource = 'route' . $resource;
                            break;
                        case 'callables':
                            $resource = 'callable/' . $resource;
                            if ((bool) $privileges) {
                                $assertion  = $privileges;
                                $privileges = null;
                            }
                            break;
                        default:
                            throw new Exception('Error Processing Request');
                    }

                    if (!$this->hasResource($resource)) {
                        $this->addResource(new Resource($resource));
                    }

                    if (is_array($privileges)) {
                        $privileges = array_map('strtolower', $privileges);
                    }
                    if (null !== $assertion) {
                        if (is_string($assertion) && !is_callable($assertion)
                            && null !== $container && $container->has($assertion)) {
                            $assertion = $container->get($assertion);
                        }

                        if (is_callable($assertion)) {
                            $assertion = new CallbackAssertion($assertion);
                        }

                        if (!$assertion instanceof AssertionInterface) {
                            throw new Exception('Error Processing Request');
                        }
                    }

                    $this->allow($roles, $resource, $privileges, $assertion);
                }
            }
        }
    }
}
