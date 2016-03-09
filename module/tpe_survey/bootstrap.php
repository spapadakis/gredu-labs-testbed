<?php

use GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity;
use GrEduLabs\Schools\Service\StaffServiceInterface;
use GrEduLabs\TpeSurvey\Action\SubmitTeachersCount;
use GrEduLabs\TpeSurvey\Action\SurveyForm;
use GrEduLabs\TpeSurvey\InputFilter\Survey;
use GrEduLabs\TpeSurvey\Middleware\SurveyFormDefaults;
use GrEduLabs\TpeSurvey\Service\SurveyService;
use GrEduLabs\TpeSurvey\Service\SurveyServiceInterface;
use Slim\App;
use Slim\Container;

/**
 * gredu_labs.
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (App $app) {
    $container = $app->getContainer();
    $events    = $container->get('events');

    $events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('GrEduLabs\\TpeSurvey\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function (Container $c) {

        $c[SurveyServiceInterface::class] = function ($c) {
            return new SurveyService();
        };

        $c[SurveyFormDefaults::class] = function ($c) {
            return new SurveyFormDefaults($c->get('view'));
        };

        $c[SurveyForm::class] = function ($c) {
            return new SurveyForm(
                $c->get(SurveyServiceInterface::class),
                $c->get(Survey::class),
                $c->get(StaffServiceInterface::class)
            );
        };

        $c[SubmitTeachersCount::class] = function ($c) {
            return new SubmitTeachersCount(
                $c->get(SurveyServiceInterface::class)
            );
        };

        $c[Survey::class] = function ($c) {
            return new Survey();
        };
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $c->get('view')->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->group('/tpe_survey', function () {
            $this->map(['GET', 'POST'], '', SurveyForm::class)->setName('tpe_survey');
            $this->post('/total-teachers', SubmitTeachersCount::class)->setName('tpe_survey.total_teachers');
        })->add(FetchSchoolFromIdentity::class);
    });

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $c->get('router')->getNamedRoute('school.staff')->add(SurveyFormDefaults::class);
    }, -10);
};
