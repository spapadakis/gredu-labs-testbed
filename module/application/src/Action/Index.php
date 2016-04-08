<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Application\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class Index
{
    /**
     * @var Twig
     */
    protected $view;

    protected $content;

    /**
     * Constructor.
     *
     * @param Twig $view
     */
    public function __construct(Twig $view, $content = 'welcome')
    {
        $this->view    = $view;
        $this->content = $content;
    }

    public function __invoke(Request $req, Response $res)
    {
        try {
            return $this->view->render($res, 'index.twig', [
                'content' => $this->content,
            ]);
        } catch (\Exception $ex) {
            return $this->view->render($res, 'index.twig', [
                'content' => 'welcome',
            ]);
        }
    }
}
