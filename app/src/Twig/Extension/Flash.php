<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Twig\Extension;

use Slim\Flash\Messages;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;

class Flash extends Twig_Extension
{
    /**
     * @var Twig_Environment
     */
    protected $environment;

    /**
     * @var Messages
     */
    protected $flash;

    /**
     * @var string
     */
    protected $template;

    public function __construct(Messages $flash, $template)
    {
        $this->flash    = $flash;
        $this->template = $template;
    }

    public function getName()
    {
        return 'slim-flash';
    }

    public function initRuntime(Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('flash', [$this, 'messages'], [
                'needs_environment' => true,
                'is_safe'           => ['all'],
            ]),
        ];
    }

    public function messages()
    {
        $response    = '';
        $allMessages = $this->flash->getMessages();

        if (!empty($allMessages)) {
            foreach ($allMessages as $class => $messages) {
                $response .= $this->environment->render($this->template, [
                    'class'    => $class,
                    'messages' => $messages,
                ]);
            }
        }

        return $response;
    }
}
