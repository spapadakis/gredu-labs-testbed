<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ReceiveEquip\Middleware;

use GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;


class SchoolReceiveEquip
{
    /**
     *
     * @var Twig
     */
    protected $view;
    protected $container;

    /**
     *
     * @var ReceiveEquipServiceInterface
     */
    protected $receiveEquipService;

    public function __construct(Twig $view, ReceiveEquipServiceInterface $receiveEquipService, $container)
    {
        $this->view           = $view;
        $this->receiveEquipService = $receiveEquipService;
        $this->container = $container;
    }

    public function __invoke(Request $req, Response $res, callable $next)
    {
        $school = $req->getAttribute('school');

        $receiveEquip = $this->receiveEquipService->findSchoolReceiveEquip($school->id);
        if ($receiveEquip) {
            $receiveEquip['items'] = array_reduce($receiveEquip['items'], function ($aggr, $item) {
                $category = $item['itemcategory_id'];
                if (!isset($aggr[$category])) {
                    $aggr[$category] = [
                        'category'      => $item['itemcategory'],
                        'count'         => 0,
                        'countAcquired' => 0,
                        'available'     => 'LATEST'
                    ];

                }
                $aggr[$category]['count'] += $item['qty'];
                $aggr[$category]['countAcquired'] += $item['qtyacquired'];

                return $aggr;
            }, []);
        }

        $this->view['receiveEquip'] = $receiveEquip;

        return $next($req, $res);
    }
}
