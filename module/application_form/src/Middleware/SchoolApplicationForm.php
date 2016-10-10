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
            $settings = $this->container->get('settings');
            $currentVersion = $settings['application_form']['itemcategory']['currentversion'];
            // get the existing (db) application form version
            $items_version = $currentVersion;
            if (isset($appForm['items']) && \count($appForm['items']) > 0) {
                $items_version = array_values($appForm['items'])[0]['version'];
            }

            $appForm['items'] = array_reduce($appForm['items'], function ($aggr, $item) use ($currentVersion, $items_version) {
                $category = $item['itemcategory_id'];
                if (!isset($aggr[$category])) {
                    $aggr[$category] = [
                        'category'      => $item['itemcategory'],
                        'count'         => 0,
                        'countAcquired' => 0,
                        'available'     => 'LATEST'
                    ];

                    /**
                     * Do mapping of old items to new only if items do exist (old form) 
                     * and the map is available at the app settings.
                     * TODO: Only one version migrations are supported. If the old items are
                     * two or more versions older, they will not be handled.
                     */
                    if ($currentVersion != $items_version &&
                        isset($this->container['settings']['application_form']['itemcategory']['map']) &&
                        $this->container['settings']['application_form']['itemcategory']['map']['fromversion'] == $items_version &&
                        $this->container['settings']['application_form']['itemcategory']['map']['toversion'] == $currentVersion &&
                        isset($this->container['settings']['application_form']['itemcategory']['map']['items'])) {

                        if (isset($this->container['settings']['application_form']['itemcategory']['map']['items'][$item['itemcategory_id']]) &&
                            intval($this->container['settings']['application_form']['itemcategory']['map']['items'][$item['itemcategory_id']]) > 0) {
                            $aggr[$category]['available'] = "MIGRATE";
                        } else {
                            $aggr[$category]['available'] = "UNAVAILABLE";
                        }
                    } elseif ($currentVersion != $items_version &&
                        isset($this->container['settings']['application_form']['itemcategory']['map']) &&
                        ($this->container['settings']['application_form']['itemcategory']['map']['fromversion'] != $items_version ||
                        $this->container['settings']['application_form']['itemcategory']['map']['toversion'] != $currentVersion)) {
                        $aggr[$category]['available'] = "UNAVAILABLE";
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
