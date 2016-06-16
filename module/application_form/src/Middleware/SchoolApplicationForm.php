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

    /**
     *
     * @var ApplicationFormServiceInterface
     */
    protected $appFormService;

    public function __construct(Twig $view, ApplicationFormServiceInterface $appFormService)
    {
        $this->view           = $view;
        $this->appFormService = $appFormService;
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
                        'category' => $item['itemcategory'],
                        'count'    => 0,
                    ];
                }
                $aggr[$category]['count'] += $item['qty'];

                return $aggr;
            }, []);
        }

        $this->view['appForm'] = $appForm;

        return $next($req, $res);
    }
}
