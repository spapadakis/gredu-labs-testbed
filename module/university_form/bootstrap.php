<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

return function (Slim\App $app) {
    $container = $app->getContainer();
    $events    = $container['events'];


    $events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('GrEduLabs\\UniversityForm\\', __DIR__ . '/src/');
           });

    $events('on', 'app.services', function ($container) {
        // actions

     $container[GrEduLabs\UniversityForm\Service\UniversityFormServiceInterface::class] = function ($c) {
            return new GrEduLabs\UniversityForm\Service\UniversityFormService();
        };


    });


  $events('on', 'app.bootstrap', function ($app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
       
        $app->group('/university-form', function () {
            $this->map(['get', 'post'], '', GrEduLabs\UniversityForm\Action\UniversityForm::class)
                ->setName('university-form');
            $this->get('/submit-success', GrEduLabs\ApplicationForm\Action\SubmitSuccess::class)
                ->setName('university-form.submit_success');
        })->add(GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class);
    });


};
