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
        $container[GrEduLabs\UniversityForm\Service\UniversityFormServiceInterface::class] = function ($c) {
            return new GrEduLabs\UniversityForm\Service\UniversityFormService($c['logger']);
        };
        $container[GrEduLabs\UniversityForm\InputFilter\UniversityForm::class] = function ($c) {
            return new GrEduLabs\UniversityForm\InputFilter\UniversityForm(
                $c->get(GrEduLabs\UniversityForm\Service\UniversityFormServiceInterface::class),
                $c->get(GrEduLabs\Schools\Service\SchoolServiceInterface::class),
                $c->get(GrEduLabs\UniversityForm\InputFilter\UniversityFormItemCollection::class)
            );
        };
        $container[GrEduLabs\UniversityForm\InputFilter\UniversityFormItem::class] = function ($c) {
            return new GrEduLabs\UniversityForm\InputFilter\UniversityFormItem(
            );
        };
        $container[GrEduLabs\UniversityForm\InputFilter\UniversityFormItemCollection::class] = function ($c) {
            return new GrEduLabs\UniversityForm\InputFilter\UniversityFormItemCollection(
                $c->get(GrEduLabs\UniversityForm\InputFilter\UniversityFormItem::class)
            );
        };
        $container[GrEduLabs\UniversityForm\Action\UniversityForm::class] = function ($c) {
            $settings = $c->get('settings');
            return new GrEduLabs\UniversityForm\Action\UniversityForm(
                $c->get('view'),
                $c->get(GrEduLabs\UniversityForm\Service\UniversityFormServiceInterface::class),
                $c->get(GrEduLabs\UniversityForm\InputFilter\UniversityForm::class),
                $c->get('authentication_service'),
                $c->get('router')->pathFor('university_form.submit_success'),
                $c
            );
        };
        $container[GrEduLabs\UniversityForm\Action\SubmitSuccess::class] = function ($c) {
            return new GrEduLabs\UniversityForm\Action\SubmitSuccess(
                $c->get('view'),
                $c->get('router')->pathFor('university_form')
            );
        };
        $container[GrEduLabs\UniversityForm\Action\UniversityFormPdf::class] = function ($c) {
            return new GrEduLabs\UniversityForm\Action\UniversityFormPdf(
                $c->get(GrEduLabs\UniversityForm\Service\UniversityFormServiceInterface::class),
                $c->get('view')
            );
        };
        $container[GrEduLabs\UniversityForm\Acl\Assertion\CanSubmit::class] = function ($c) {
            return new GrEduLabs\UniversityForm\Acl\Assertion\CanSubmit(
                $c->get('authentication_service'),
                $c->get(GrEduLabs\UniversityForm\Service\UniversityFormServiceInterface::class)
            );
        };
        $container[GrEduLabs\UniversityForm\Middleware\SchoolUniversityForm::class] = function ($c) {
            return new GrEduLabs\UniversityForm\Middleware\SchoolUniversityForm(
                $c->get('view'),
                $c->get(GrEduLabs\UniversityForm\Service\UniversityFormServiceInterface::class),
                $c
            );
        };
    });
    $events('on', 'app.bootstrap', function ($app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
        $container['router']->getNamedRoute('school')
                ->add(GrEduLabs\UniversityForm\Middleware\SchoolUniversityForm::class);
        $app->group('/university-form', function () {
            $this->map(['get', 'post'], '', GrEduLabs\UniversityForm\Action\UniversityForm::class)
                ->add(GrEduLabs\Application\Middleware\AddCsrfToView::class)
                ->add('csrf')
                ->setName('university_form');
            $this->get('/submit-success', GrEduLabs\UniversityForm\Action\SubmitSuccess::class)
                ->setName('university_form.submit_success');
            $this->get('/report', GrEduLabs\UniversityForm\Action\UniversityFormPdf::class)
                ->setName('university_form.report');
        })->add(GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class);
    });
};
