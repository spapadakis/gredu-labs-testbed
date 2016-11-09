<?php
/**
 * gredu_labs.
 *
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 *
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */
use Slim\Http\Request;
use Slim\Http\Response;
use RedBeanPHP\R;

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
    });

    $events('on', 'app.services', function ($container) {
        $container[GrEduLabs\ReceiveEquip\Middleware\HandleEmptyPosts::class] = function ($c) {
            return new GrEduLabs\ReceiveEquip\Middleware\HandleEmptyPosts(
                $c->get('view'),
                $c->get(GrEduLabs\ReceiveEquip\Service\ReceiveEquipServiceInterface::class),
                $c['flash'],
                $c
            );
        };
    }, -100000);

    $events('on', 'app.bootstrap', function ($app, $container) {
        $container['view']->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');

        $app->group('/receive-equip', function () {
            $this->map(['get', 'post'], '', GrEduLabs\ReceiveEquip\Action\ReceiveEquip::class)
                ->add(GrEduLabs\Application\Middleware\AddCsrfToView::class)
                ->add('csrf')
                ->add(GrEduLabs\ReceiveEquip\Middleware\HandleEmptyPosts::class)
                ->setName('receive_equip');
            $this->get('/submit-success', GrEduLabs\ReceiveEquip\Action\SubmitSuccess::class)
                ->setName('receive_equip.submit_success');
            $this->get('/report', GrEduLabs\ReceiveEquip\Action\ReceiveEquipPdf::class)
                ->setName('receive_equip.report');
        })->add(GrEduLabs\Schools\Middleware\FetchSchoolFromIdentity::class);

        $app->get('/receive-equip/receive-doc/{fn}', function (Request $req, Response $res) use ($container) {
            $route = $req->getAttribute('route');
            $fn = $route->getArgument('fn');
/*            $container["logger"]->info(sprintf('filename = %s  url=%s', $fn, path_for('receive_equip.receive_doc', [
    'fn' => form.values.received_document,])
)); */

            $file = $container['settings']['receive_equip']['file_upload_path'] . "/" . $fn;
            $response = $res->withHeader('Content-Description', 'File Transfer')
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', 'attachment;filename="' . basename($file) . '"')
                ->withHeader('Expires', '0')
                ->withHeader('Cache-Control', 'must-revalidate')
                ->withHeader('Content-Length', filesize($file));

            readfile($file);

            return $response;
        })->setName('receive_equip.receive_doc');


/******************* only for tests ***************************/
        $app->get('/receive-equip/undo-submit/{applicationform_id}', function (Request $req, Response $res) use ($container) {
            $route = $req->getAttribute('route');
            $applicationform_id = $route->getArgument('applicationform_id');

            $sql = 'update `applicationform` set `approved`=1, `received_ts`=null where `id`=' . $applicationform_id;
            R::exec($sql);

            return $res->withRedirect("/receive-equip");

        })->setName('receive_equip.undosubmit');
/******************  /only for tests ***************************/
    });
};
