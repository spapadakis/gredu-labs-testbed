<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\Action\AppForm;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class Index
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        return $this->view->render($res, 'app-form/index.twig', [
            'assets' => [
                [
                    'typeName' => 'ΒΙΝΤΕΟΠΡΟΒΟΛΕΑΣ',
                    'labName'  => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 1',
                    'quantity' => 2,
                ],
                [
                    'typeName' => 'LAPTOP',
                    'labName'  => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 2',
                    'quantity' => 1,
                ],
                [
                    'typeName' => 'ΠΛΗΚΤΡΟΛΟΓΙΟ',
                    'labName'  => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 2',
                    'quantity' => 10,
                ],
            ],
            'for_choices' => [
                [
                    'label' => 'ΠΛΗΡΕΣ ΕΡΓΑΣΤΗΡΙΟ',
                    'value' => 'ΠΛΗΡΕΣ ΕΡΓΑΣΤΗΡΙΟ',
                ],
                [
                    'label' => 'ΑΝΑΒΑΘΜΙΣΗ ΕΡΓΑΣΤΗΡΙΟΥ',
                    'value' => 'ΑΝΑΒΑΘΜΙΣΗ ΕΡΓΑΣΤΗΡΙΟΥ',
                ],
                [
                    'label' => 'ΚΙΝΗΤΟ ΕΡΓΑΣΤΗΡΙΟ',
                    'value' => 'ΚΙΝΗΤΟ ΕΡΓΑΣΤΗΡΙΟ',
                ],
            ],
            'lab_choices' => [
                [
                    'value' => 1,
                    'label' => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 1',
                ],
                [
                    'value' => 2,
                    'label' => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 2',
                ],
            ],
            'type_choices' => [
                [
                    'value' => 1,
                    'label' => 'ΒΙΝΤΕΟΠΡΟΒΟΛΕΑΣ',
                ],
                [
                    'value' => 3,
                    'label' => 'LAPTOP',
                ],
                [
                    'value' => 2,
                    'label' => 'ΠΛΗΚΤΡΟΛΟΓΙΟ',
                ],
            ],
        ]);
    }
}
