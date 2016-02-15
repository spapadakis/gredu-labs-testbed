<?php
/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace SchSSO\Action;

use phpCAS;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Logout
{
    /**
     * @var callable
     */
    protected $initCas;

    public function __construct(
        callable $initCas
    ) {
        $this->initCas = $initCas;
    }

    public function __invoke(ServerRequestInterface $req, Response $res)
    {
        call_user_func($this->initCas);
        phpCAS::logout();
    }
}
