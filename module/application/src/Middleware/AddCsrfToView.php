<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Csrf\Guard;
use Slim\Views\Twig;

class AddCsrfToView
{
    private $view;

    private $csrf;

    public function __construct(Twig $view, Guard $csrf)
    {
        $this->view = $view;
        $this->csrf = $csrf;
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $res, callable $next)
    {
        $nameKey  = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $name     = $req->getAttribute($nameKey);
        $value    = $req->getAttribute($valueKey);

        $this->view['csrf'] = [
            'name_key'  => $nameKey,
            'value_key' => $valueKey,
            'name'      => $name,
            'value'     => $value,
        ];

        return $next($req, $res);
    }
}
