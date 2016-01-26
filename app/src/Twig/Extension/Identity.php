<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Twig\Extension;

use Twig_Extension;
use Twig_SimpleFunction;
use Zend\Authentication\AuthenticationServiceInterface;

class Identity extends Twig_Extension
{
    /**
     * @var RouterInterface
     */
    private $authService;

    public function __construct(AuthenticationServiceInterface $authService)
    {
        $this->authService = $authService;
    }

     /**
     * Extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'slim-identity';
    }

    /**
     * Callback for twig.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('identity', [$this, 'identity']),
        ];
    }

    /**
     *
     */
    public function identity($prop = null)
    {
        if (!$this->authService->hasIdentity()) {
            return;
        }

        $identity = $this->authService->getIdentity();
        if (null !== $prop) {
            return $identity->{$prop};
        }

        return $identity;
    }
}
