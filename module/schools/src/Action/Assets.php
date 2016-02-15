<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Schools\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class Assets
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        return $this->view->render($res, 'schools/assets.twig', [
            'assets' => [
                [
                    'id'              => 1,
                    'type'            => 1,
                    'typeName'        => 'ΒΙΝΤΕΟΠΡΟΒΟΛΕΑΣ',
                    'lab'             => 1,
                    'labName'         => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 1',
                    'quantity'        => 2,
                    'acquisitionYear' => '2001',
                    'comments'        => 'test',
                ],
                [
                    'id'              => 2,
                    'type'            => 3,
                    'typeName'        => 'LAPTOP',
                    'lab'             => 2,
                    'labName'         => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 2',
                    'quantity'        => 1,
                    'acquisitionYear' => '2003',
                    'comments'        => 'test',
                ],
                [
                    'id'              => 3,
                    'type'            => 2,
                    'typeName'        => 'ΠΛΗΚΤΡΟΛΟΓΙΟ',
                    'lab'             => 2,
                    'labName'         => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 2',
                    'quantity'        => 10,
                    'acquisitionYear' => '2008',
                    'comments'        => 'test',
                ],
            ],
        ]);
    }
}
