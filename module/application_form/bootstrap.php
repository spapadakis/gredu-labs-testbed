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

    $events('on', 'app.autoload', function ($stop, $autoloader) {
        $autoloader->addPsr4('GrEduLabs\\ApplicationForm\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function ($stop, $container) {

        $container[GrEduLabs\ApplicationForm\Service\ApplicationFormServiceInterface::class] = function ($c) {
            return new GrEduLabs\ApplicationForm\Service\ApplicationFormService();
        };

        $container[GrEduLabs\ApplicationForm\InputFilter\ApplicationForm::class] = function ($c) {
            return new GrEduLabs\ApplicationForm\InputFilter\ApplicationForm(
                $c->get(GrEduLabs\ApplicationForm\Service\ApplicationFormServiceInterface::class),
                $c->get(GrEduLabs\Schools\Service\SchoolServiceInterface::class),
                $c->get(GrEduLabs\ApplicationForm\InputFilter\ApplicationFormItemCollection::class)
            );
        };

        $container[GrEduLabs\ApplicationForm\InputFilter\ApplicationFormItem::class] = function ($c) {
            return new GrEduLabs\ApplicationForm\InputFilter\ApplicationFormItem(
                $c->get(GrEduLabs\Schools\Service\LabServiceInterface::class),
                $c->get(GrEduLabs\Schools\Service\AssetServiceInterface::class)
            );
        };

        $container[GrEduLabs\ApplicationForm\InputFilter\ApplicationFormItemCollection::class] = function ($c) {
            return new GrEduLabs\ApplicationForm\InputFilter\ApplicationFormItemCollection(
                $c->get(GrEduLabs\ApplicationForm\InputFilter\ApplicationFormItem::class)
            );
        };

        $container[GrEduLabs\ApplicationForm\Action\ApplicationForm::class] = function ($c) {
            return new GrEduLabs\ApplicationForm\Action\ApplicationForm(
                $c->get('view'),
                $c->get(GrEduLabs\Schools\Service\AssetServiceInterface::class),
                $c->get(GrEduLabs\ApplicationForm\Service\ApplicationFormServiceInterface::class),
                $c->get(GrEduLabs\ApplicationForm\InputFilter\ApplicationForm::class)
            );
        };
    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->map(['get', 'post'], '/application-form', GrEduLabs\ApplicationForm\Action\ApplicationForm::class)
            ->add(GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class)
            ->add(GrEduLabs\Application\Middleware\AddCsrfToView::class)
            ->add('csrf')
            ->setName('application_form');
    });
};
