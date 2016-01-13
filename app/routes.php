<?php

$app->get('/', function ($request, $response, $args) use ($app) {
    $container = $app->getContainer();
    $logger = $container['logger'];
    $view = $container['view'];

    $logger->info('Home page dispatched');
    $view->render($response, 'home.twig');

    return $response;
});
