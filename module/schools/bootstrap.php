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

    $container['autoloader']->addPsr4('GrEduLabs\\Schools\\', __DIR__ . '/src/');

    $container[GrEduLabs\Schools\Action\Index::class] = function ($c) {
        return new GrEduLabs\Schools\Action\Index(
            $c->get('view'),
            $c->get('schoolservice')
        );
    };

    $container[GrEduLabs\Schools\Action\Staff::class] = function ($c) {
        return new GrEduLabs\Schools\Action\Staff(
            $c->get('view'),
            $c->get('staffservice')
            );
    };

    $container[GrEduLabs\Schools\Action\StaffCreate::class] = function ($c) {
        return new GrEduLabs\Schools\Action\StaffCreate(
            $c->get('staffservice')
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

    $container['schoolservice'] = function($c){
        return new GrEduLabs\Schools\Service\SchoolService();
    };

    $container['staffservice'] = function($c){
        return new GrEduLabs\Schools\Service\StaffService(
            $c->get('schoolservice')
        );
    };

    $container['labservice'] = function($c){
        return new GrEduLabs\Schools\Service\LabService(
            $c->get('schoolservice'),
            $c->get('staffservice')
        );
    };

    $container['assetservice'] = function($c){
        return new GrEduLabs\Schools\Service\AssetService(
            $c->get('schoolservice'),
            $c->get('labservice')
        );
    };


    $events = $container['events'];

    $events('on', 'bootstrap', function () use ($container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
    });

    $app->group('/school', function () {
        $this->get('', GrEduLabs\Schools\Action\Index::class)->setName('school');
        $this->get('/staff', GrEduLabs\Schools\Action\Staff::class)->setName('school.staff');
        $this->post('/staff', GrEduLabs\Schools\Action\StaffCreate::class)->setName('school.staffcreate');
        $this->get('/labs', GrEduLabs\Schools\Action\Labs::class)->setName('school.labs');
        $this->post('/labs', GrEduLabs\Schools\Action\LabCreate::class)->setName('school.labcreate');
        $this->get('/assets', GrEduLabs\Schools\Action\Assets::class)->setName('school.assets');
    });
};
