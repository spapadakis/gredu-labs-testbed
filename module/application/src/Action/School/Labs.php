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

class Labs
{
    protected $view;

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function __invoke(Request $req, Response $res, array $args = [])
    {
        return $this->view->render($res, 'school/labs.twig', [
            'labs' => [
                [
                    'id'              => 1,
                    'name'            => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 1',
                    'type'            => 1,
                    'typeName'        => 'ΕΡΓΑΣΤΗΡΙΟ',
                    'responsible'     => 1,
                    'responsibleName' => 'Γιώργος Τάδε',
                    'area'            => 24,
                ],
                [
                    'id'              => 2,
                    'name'            => 'ΕΡΓΑΣΤΗΡΙΟ ΠΛΗΡΟΦ/ΚΗΣ 2',
                    'type'            => 2,
                    'typeName'        => 'ΑΙΘΟΥΣΑ',
                    'responsible'     => 2,
                    'responsibleName' => 'Νίκος Τάδε',
                    'area'            => 50,
                ],
            ],
            'staff' => [
                [
                    'value' => 1,
                    'label' => 'Γιώργος Τάδε',

                ],
                [
                    'value' => 2,
                    'label' => 'Νίκος Τάδε',
                ],
            ],
            'lab_types' => [
                [
                    'value' => 1,
                    'label' => 'ΕΡΓΑΣΤΗΡΙΟ',
                ],
                [
                    'value' => 2,
                    'label' => 'ΑΙΘΟΥΣΑ',
                ],
                [
                    'value' => 3,
                    'label' => 'ΓΡΑΦΕΙΟ',
                ],
            ],
            'lessons' => [
                [
                    'value' => 1,
                    'label' => 'ΦΥΣΙΚΗ',
                ],
                [
                    'value' => 2,
                    'label' => 'ΠΛΗΡΟΦΟΡΙΚΗ',
                ],
            ],
        ]);
    }
}
