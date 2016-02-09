<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Application\Action\School;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class Staff
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        return $this->view->render($res, 'school/staff.twig', [
            'staff' => array_fill(0, 1,
                [
                    'id'            => 150,
                    'name'          => 'test',
                    'surname'       => 'test2',
                    'branch'        => 'Some branch',
                    'telephone'     => '2413123212',
                    'email'         => 'test@test.com',
                    'positionLabel' => 'Εκπαδευτικός',
                    'position'      => 1,
                ]
            ),
            'positions' => [
                ['value' => 1, 'label' => 'Εκπαδευτικός'],
                ['value' => 2, 'label' => 'Διευθυντής σχολείου'],
                ['value' => 3, 'label' => 'Υπεύθυνος εργαστηρίου'],
            ],
            'branches' => [
                ['value' => 'branch1', 'label' => 'branch'],
                ['value' => 'branch2', 'label' => 'branch2'],
            ],
        ]);
    }
}
