<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Action\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class LoginSso
{

    /**
     * @var callable
     */
    protected $authenticate;

    /**
     * Constructor
     * @param Twig $view
     */
    public function __construct(callable $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, array $args = [])
    {
        $authenticate = $this->authenticate;
        $result       = $authenticate();

        var_dump($result);

        return $res;
    }
}
