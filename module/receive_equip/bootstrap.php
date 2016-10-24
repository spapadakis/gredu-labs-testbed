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
        $autoloader->addPsr4('GrEduLabs\\ReceiveEquip\\', __DIR__ . '/src/');
    });

    $events('on', 'app.services', function ($container) {

        $container[GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\Service\ReceiveEquipService($c['logger']);
        };

        $container[GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquip::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquip(
                $c->get(GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface::class),
                $c->get(GrEduLabs\Schools\Service\SchoolServiceInterface::class),
                $c->get(GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquipItemCollection::class)
            );
        };

        $container[GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquipItem::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquipItem(
            );
        };

        $container[GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquipItemCollection::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquipItemCollection(
                $c->get(GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquipItem::class)
            );
        };

        $container[GrEduLabs\ReceiveEquip\Action\ReceiveEquip::class] = function ($c) {
            $settings = $c->get('settings');
            return new GrEduLabs\ReceiveEquip\Action\ReceiveEquip(
                $c->get('view'),
                $c->get(GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface::class),
                $c->get(GrEduLabs\ReceiveEquip\InputFilter\ReceiveEquip::class),
                $c->get('authentication_service'),
                $c->get('router')->pathFor('receive_equip.submit_success'),
                $c['flash'],
                $c
            );
        };

        $container[GrEduLabs\ReceiveEquip\Action\SubmitSuccess::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\Action\SubmitSuccess(
                $c->get('view'),
                $c->get('router')->pathFor('receive_equip')
            );
        };

        $container[GrEduLabs\ReceiveEquip\Action\ReceiveEquipPdf::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\Action\ReceiveEquipPdf(
                $c->get(GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface::class),
                $c->get('view')
            );
        };

        $container[GrEduLabs\ReceiveEquip\Acl\Assertion\CanSubmit::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\Acl\Assertion\CanSubmit(
                $c->get('authentication_service'),
                $c->get(GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface::class)
            );
        };

        $container[GrEduLabs\ReceiveEquip\Middleware\SchoolReceiveEquip::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\Middleware\SchoolReceiveEquip(
                $c->get('view'),
                $c->get(GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface::class),
                $c
            );
        };
    });

    $events('on', 'app.bootstrap', function ($app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->group('/receive-equip', function () {
            $this->map(['get', 'post'], '', GrEduLabs\ReceiveEquip\Action\ReceiveEquip::class)
                ->add(GrEduLabs\Application\Middleware\AddCsrfToView::class)
                ->add('csrf')
                ->setName('receive_equip');
            $this->get('/submit-success', GrEduLabs\ReceiveEquip\Action\SubmitSuccess::class)
                ->setName('receive_equip.submit_success');
            $this->get('/report', GrEduLabs\ReceiveEquip\Action\ReceiveEquipPdf::class)
                ->setName('receive_equip.report');
        })->add(GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class);
    });
};
