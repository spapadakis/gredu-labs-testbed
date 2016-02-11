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
            return new GrEduLabs\Schools\Action\Index($c->get('view'));
        };

        $container[GrEduLabs\Schools\Action\Staff::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Staff($c->get('view'));
        };

        $container[GrEduLabs\Schools\Action\Labs::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Labs($c->get('view'));
        };

        $container[GrEduLabs\Schools\Action\Assets::class] = function ($c) {
            return new GrEduLabs\Schools\Action\Assets($c->get('view'));
        };
    });

    $events('on', 'app.bootstrap', function ($stop, $app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->group('/school', function () {
            $this->get('', GrEduLabs\Schools\Action\Index::class)->setName('school');
            $this->get('/staff', GrEduLabs\Schools\Action\Staff::class)->setName('school.staff');
            $this->get('/labs', GrEduLabs\Schools\Action\Labs::class)->setName('school.labs');
            $this->get('/assets', GrEduLabs\Schools\Action\Assets::class)->setName('school.assets');
        });
    });


};
