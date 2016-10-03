<?php

/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace GrEduLabs\ApplicationForm\Middleware;

use GrEduLabs\ApplicationForm\Service\ApplicationFormServiceInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;


class SchoolApplicationForm
{
    /**
     *
     * @var Twig
     */
    protected $view;
    protected $container;

    /**
     *
     * @var ApplicationFormServiceInterface
     */
    protected $appFormService;

    public function __construct(Twig $view, ApplicationFormServiceInterface $appFormService, $container)
    {
        $this->view           = $view;
        $this->appFormService = $appFormService;
        $this->container = $container;
    }

    public function __invoke(Request $req, Response $res, callable $next)
    {
        $school = $req->getAttribute('school');

        $appForm = $this->appFormService->findSchoolApplicationForm($school->id);
        if ($appForm) {
            $appForm['items'] = array_reduce($appForm['items'], function ($aggr, $item) {
                $category = $item['itemcategory_id'];
                if (!isset($aggr[$category])) {
                    $aggr[$category] = [
                        'category'      => $item['itemcategory'],
                        'count'         => 0,
                        'countAcquired' => 0,
                    ];
                    $this->container['logger']->info("Checking for migration..."); // TODO 
                    // TODO CHECK VERSIONS!!!! 
                    if (isset($this->container['settings']['application_form']['itemcategory']['map'])
                        && isset($this->container['settings']['application_form']['itemcategory']['map']['items'][$item['itemcategory_id']])
                        && intval($this->container['settings']['application_form']['itemcategory']['map']['items'][$item['itemcategory_id']]) > 0) {
                        $aggr[$category]['category_id_new'] = $this->container['settings']['application_form']['itemcategory']['map']['items'][$item['itemcategory_id']];
                        $aggr[$category]['available'] = true;
                    } else {
                        $aggr[$category]['category_id_new'] = null;
                        $aggr[$category]['available'] = false;
                    }
                }
                $aggr[$category]['count'] += $item['qty'];
                $aggr[$category]['countAcquired'] += $item['qtyacquired'];

                return $aggr;
            }, []);
        }

        $this->view['appForm'] = $appForm;

        return $next($req, $res);
    }
}
