<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

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
    $events    = $container['events'];

$events('on', 'app.autoload', function ($autoloader) {
        $autoloader->addPsr4('GrEduLabs\\TeacherForm\\', __DIR__ . '/src/');
    });

$events('on', 'app.services', function ($container) {

        $container[GrEduLabs\TeacherForm\Service\TeacherFormServiceInterface::class] = function ($c) {
            return new GrEduLabs\TeacherForm\Service\TeacherFormService();
        };

        $container[GrEduLabs\TeacherForm\InputFilter\TeacherForm::class] = function ($c) {
            return new GrEduLabs\TeacherForm\InputFilter\TeacherForm();
        };


 	    $container[GrEduLabs\TeacherForm\Action\TeacherForm::class] = function ($c) {
               return new GrEduLabs\TeacherForm\Action\TeacherForm(
                $c->get('view'),
                $c->get(GrEduLabs\TeacherForm\Service\TeacherFormServiceInterface::class),
                $c->get(GrEduLabs\TeacherForm\InputFilter\TeacherForm::class),
                $c->get('router')->pathFor('teacher_form.submit_success'),
                $c);
        };

        $container[GrEduLabs\TeacherForm\Action\SubmitSuccess::class] = function ($c) {
            return new GrEduLabs\TeacherForm\Action\SubmitSuccess(
                $c->get('view'),
                $c->get('router')->pathFor('teacher_form')
            );
        };
});


$events('on', 'app.bootstrap', function ($app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

     $app->group('/teacher-form', function () {
            $this->map(['get', 'post'], '', GrEduLabs\TeacherForm\Action\TeacherForm::class)
                ->add(GrEduLabs\Application\Middleware\AddCsrfToView::class)
                ->add('csrf')
                ->setName('teacher_form');
            $this->get('/submit-success', GrEduLabs\TeacherForm\Action\SubmitSuccess::class)
                ->setName('teacher_form.submit_success');
        });
    });

};
