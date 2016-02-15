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
        $autoloader->addPsr4('GrEduLabs\\Schools\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function ($stop, $container) {
        $container[GrEduLabs\Schools\Action\Index::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Index(
                 $c->get('view'),
                 $c->get('schoolservice')
            );
        };

        $container[GrEduLabs\Schools\Action\Staff\ListAll::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Staff\ListAll(
                $c->get('view'),
                $c->get(GrEduLabs\Schools\Service\StaffService::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Staff\PersistTeacher::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Staff\PersistTeacher(
                $c->get(GrEduLabs\Schools\Service\StaffService::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Staff\DeleteTeacher::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Staff\DeleteTeacher(
                $c->get(GrEduLabs\Schools\Service\StaffService::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Labs::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Labs(
                $c->get('view')
            );
        };

        $container[GrEduLabs\Schools\Action\LabCreate::class] = function ($c) {
            return new GrEduLabs\Schools\Action\LabCreate(
                 $c->get('labservice')
            );
        };

        $container[GrEduLabs\Schools\Action\Assets::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Assets($c->get('view'));
        };

        $container['schoolservice'] = function ($c) {
            return new GrEduLabs\Schools\Service\SchoolService();
        };

        $container[GrEduLabs\Schools\InputFilter\Teacher::class] = function ($c) {
            return new GrEduLabs\Schools\InputFilter\Teacher();
        };

        $container[GrEduLabs\Schools\Service\StaffService::class] = function ($c) {
            return new GrEduLabs\Schools\Service\StaffService();
        };

        $container[GrEduLabs\Schools\Middleware\InputFilterTeacher::class] = function ($c) {
            return new GrEduLabs\Schools\Middleware\InputFilterTeacher(
                $c->get(GrEduLabs\Schools\InputFilter\Teacher::class)
            );
        };

        $container['labservice'] = function ($c) {
            return new GrEduLabs\Schools\Service\LabService(
                $c->get('schoolservice'),
                $c->get('staffservice')
            );
        };

        $container['assetservice'] = function ($c) {
            return new GrEduLabs\Schools\Service\AssetService(
                $c->get('schoolservice'),
                $c->get('labservice')
            );
        };

        $container[GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class] = function ($c) {
            return new GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity($c['authentication_service']);
        };

    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->group('/school', function () {
            $this->get('', GrEduLabs\Schools\Action\Index::class)->setName('school');
            $this->get('/staff', GrEduLabs\Schools\Action\Staff\ListAll::class)->setName('school.staff');
            $this->post('/staff', GrEduLabs\Schools\Action\Staff\PersistTeacher::class)
                ->add(GrEduLabs\Schools\Middleware\InputFilterTeacher::class)
                ->setName('school.staffcreate');
            $this->delete('/staff/{id:[1-9][0-9]*}', GrEduLabs\Schools\Action\Staff\DeleteTeacher::class)
                ->setName('school.staffdelete');

            $this->get('/labs', GrEduLabs\Schools\Action\Labs::class)->setName('school.labs');
            $this->post('/labs', GrEduLabs\Schools\Action\LabCreate::class)->setName('school.labcreate');
            $this->get('/assets', GrEduLabs\Schools\Action\Assets::class)->setName('school.assets');
        })->add(GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class);
    });
};
