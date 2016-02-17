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

        // actions

        $container[GrEduLabs\Schools\Action\Index::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Index(
                 $c->get('view'),
                 $c->get('schoolservice')
            );
        };

        $container[GrEduLabs\Schools\Action\Staff\ListAll::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Staff\ListAll(
                $c->get('view'),
                $c->get(GrEduLabs\Schools\Service\StaffServiceInterface::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Staff\PersistTeacher::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Staff\PersistTeacher(
                $c->get(GrEduLabs\Schools\Service\StaffServiceInterface::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Staff\DeleteTeacher::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Staff\DeleteTeacher(
                $c->get(GrEduLabs\Schools\Service\StaffServiceInterface::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Lab\ListAll::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Lab\ListAll(
                $c->get('view'),
                $c->get('labservice'),
                $c->get('staffservice')
            );
        };

        $container[GrEduLabs\Schools\Action\Lab\PersistLab::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Lab\PersistLab(
                 $c->get('labservice')
            );
        };

        $container[GrEduLabs\Schools\Action\Assets\ListAssets::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Assets\ListAssets(
                $c->get('view'),
                $c->get(GrEduLabs\Schools\Service\AssetServiceInterface::class),
                $c->get(GrEduLabs\Schools\Service\SchoolAssetsInterface::class),
                $c->get(GrEduLabs\Schools\Service\LabServiceInterface::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Assets\PersistAsset::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Assets\PersistAsset(
                $c->get(GrEduLabs\Schools\Service\SchoolAssetsInterface::class)
            );
        };

        $container[GrEduLabs\Schools\Action\Assets\DeleteAsset::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Assets\DeleteAsset(
                $c->get(GrEduLabs\Schools\Service\SchoolAssetsInterface::class)
            );
        };

        // services

        $container['schoolservice'] = function ($c) {
            return $c->get(GrEduLabs\Schools\Service\SchoolServiceInterface::class);
        };

        $container['staffservice'] = function ($c) {
            return $c->get(GrEduLabs\Schools\Service\StaffServiceInterface::class);
        };

        $container['labservice'] = function ($c) {
            return $c->get(GrEduLabs\Schools\Service\LabServiceInterface::class);
        };

        $container[GrEduLabs\Schools\Service\SchoolServiceInterface::class] = function ($c) {
            return new GrEduLabs\Schools\Service\SchoolService();
        };

        $container[GrEduLabs\Schools\Service\StaffServiceInterface::class] = function ($c) {
            return new GrEduLabs\Schools\Service\StaffService();
        };

        $container[GrEduLabs\Schools\Service\LabServiceInterface::class] = function ($c) {
            return new GrEduLabs\Schools\Service\LabService(
                $c->get(GrEduLabs\Schools\Service\SchoolServiceInterface::class),
                $c->get(GrEduLabs\Schools\Service\StaffServiceInterface::class)
            );
        };

        $container[GrEduLabs\Schools\Service\AssetServiceInterface::class] = function ($c) {
            return new GrEduLabs\Schools\Service\AssetService();
        };

        $container[GrEduLabs\Schools\Service\SchoolAssetsInterface::class] = function ($c) {
            return $c->get(GrEduLabs\Schools\Service\AssetServiceInterface::class);
        };

        // middleware 

        $container[GrEduLabs\Schools\Middleware\InputFilterTeacher::class] = function ($c) {
            return new GrEduLabs\Schools\Middleware\InputFilterTeacher(
                $c->get(GrEduLabs\Schools\InputFilter\Teacher::class)
            );
        };

        $container[GrEduLabs\Schools\Middleware\InputFilterSchoolAsset::class] = function ($c) {
            return new GrEduLabs\Schools\Middleware\InputFilterSchoolAsset(
                $c->get(GrEduLabs\Schools\InputFilter\SchoolAsset::class)
            );
        };

        $container[GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class] = function ($c) {
            return new GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity($c['authentication_service']);
        };

        // inputfilters

        $container[GrEduLabs\Schools\InputFilter\Teacher::class] = function ($c) {
            return new GrEduLabs\Schools\InputFilter\Teacher();
        };

        $container[GrEduLabs\Schools\InputFilter\SchoolAsset::class] = function ($c) {
            return new GrEduLabs\Schools\InputFilter\SchoolAsset(
                $c->get(GrEduLabs\Schools\Service\LabServiceInterface::class),
                $c->get(GrEduLabs\Schools\Service\AssetServiceInterface::class)
            );
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

            $this->get('/labs', GrEduLabs\Schools\Action\Lab\ListAll::class)->setName('school.labs');
            $this->post('/labs', GrEduLabs\Schools\Action\Lab\PersistLab::class)->setName('school.labcreate');

            $this->get('/assets', GrEduLabs\Schools\Action\Assets\ListAssets::class)->setName('school.assets');
            $this->post('/assets', GrEduLabs\Schools\Action\Assets\PersistAsset::class)
                ->add(GrEduLabs\Schools\Middleware\InputFilterSchoolAsset::class);
            $this->delete('/assets', GrEduLabs\Schools\Action\Assets\DeleteAsset::class);

        })->add(GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class);
    });
};
