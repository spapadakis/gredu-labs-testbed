<?php

use GrEduLabs\Schools\InputFilter\School as SchoolInputFilter;
use GrEduLabs\Schools\Service\AssetServiceInterface;
use GrEduLabs\Schools\Service\LabServiceInterface;
use GrEduLabs\Schools\Service\SchoolServiceInterface;
use SchMM\FetchUnit;
use SchSync\Middleware\CreateLabs;
use SchSync\Middleware\CreateSchool;
use SchSync\Middleware\CreateUser;
use Slim\App;
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
        $autoloader->addPsr4('SchSync\\', __DIR__ . '/src');
    });

    $events('on', 'app.services', function ($container) {
        $container[CreateUser::class] = function ($c) {
            return new CreateUser(
                $c->get('authentication_service'),
                $c->get('router')->pathFor('user.login'),
                $c->get('router')->pathFor('user.logout.sso'),
                $c->get('flash'),
                $c->get('logger')
            );
        };
        $container[CreateSchool::class] = function ($c) {
            return new CreateSchool(
                $c->get('ldap'),
                $c->get(FetchUnit::class),
                $c->get('authentication_service'),
                $c->get(SchoolServiceInterface::class),
                $c->get(SchoolInputFilter::class),
                $c->get('router')->pathFor('user.login'),
                $c->get('router')->pathFor('user.logout.sso'),
                $c->get('flash'),
                $c->get('logger')
            );
        };

        $container[\SchSync\SyncFromInventory::class] = function ($c) {
            $settings = $c->get('settings');
            $categoryMap = isset($settings['inventory']['category_map'])
                ? $settings['inventory']['category_map'] : [];

            return new \SchSync\SyncFromInventory(
                $c->get(LabServiceInterface::class),
                $c->get(AssetServiceInterface::class),
                $c->get('SchInventory\\Service'),
                $c->get(SchoolServiceInterface::class),
                $c->get('logger'),
                $categoryMap
           );
        };

        $container[CreateLabs::class] = function ($c) {
            return new CreateLabs(
                $c->get(LabServiceInterface::class),
                $c->get(\SchSync\SyncFromInventory::class),
                $c->get('authentication_service')
            );
        };

        $container[SchSync\Action\Sync::class] = function ($c) {

            return new SchSync\Action\Sync(
                $c->get(\SchSync\SyncFromInventory::class),
                $c->get('authentication_service')
            );
        };
    });

    $events('on', 'app.bootstrap', function ($app, $container) {
        $app->get('/sch_sync/sync', SchSync\Action\Sync::class)->setName('sch_sync/sync');
    });

    $events('on', 'app.bootstrap', function ($app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
        $container['router']->getNamedRoute('user.login.sso')
            ->add(CreateUser::class)
            ->add(CreateSchool::class)
            ->add(CreateLabs::class);
    }, -10);
};
