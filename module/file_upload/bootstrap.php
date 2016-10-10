<?php

use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();
    $events    = $container['events'];

    $events('on', 'app.bootstrap', function (App $app, Container $c) {
        $app->get('/file-upload', function (Request $req, Response $res) use ($c) {
            $view = $c->get('view');
            $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
            return $view->render($res, 'index.twig');
        })->setName('file_upload_view');

        $app->post('/upload', function (Request $req, Response $res, $args) use ($c, $app){
            $files = $req->getUploadedFiles();
            if (empty($files['newfile'])) {
                throw new Exception('Expected a newfile');
            }
 
            $newfile = $files['newfile'];

            if ($newfile->getError() === UPLOAD_ERR_OK) {
                $uploadFileName = $newfile->getClientFilename();
                $newfile->moveTo($c['settings']['application_form']['file_upload_path'] . $uploadFileName);
                $view = $c->get('view');
                $view->getEnvironment()->getLoader()->prependPath(__DIR__ . '/templates');
                return $view->render($res, 'uploadedok.twig');
            }
        })->setName('file_upload');
    });
};