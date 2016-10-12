<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\EnableDBLogin\Action;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class Index {

    protected $_c;

    /**
     * Constructor.
     *
     * @param Twig $view
     */
    public function __construct(Container $c) {
        $this->_c = $c;
    }

    public function __invoke(Request $req, Response $res) {
        return $res->withRedirect($this->_c['router']->pathFor('index'));
    }

}
