<?php
/**
 * gredu_labs
 * 
 * @link https://github.com/eellak/gredu_labs for the canonical source repository
 * @copyright Copyright (c) 2008-2015 Greek Free/Open Source Software Society (https://gfoss.ellak.gr/)
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */


$app->get('/', function ($request, $response, $args) {
    $logger = $this->get('logger');
    $view = $this->get('view');
    $identity = $this->get('maybe_identity');

    $logger->info('Home page dispatched');
    $view->render($response, 'home.twig', [
        'user' => $identity('uid'),
    ]);

    return $response;
})->setName('index');

// authentication

$app->group('/user', function () {
    $this->map(['GET', 'POST'], '/login', 'GrEduLabs\\Action\\User\\Login')->setName('user.login');
    $this->get('/login-sso', 'GrEduLabs\\Action\\User\\LoginSso')->setName('user.loginSso');
    $this->get('/logout', 'GrEduLabs\\Action\\User\\Logout')->setName('user.logout');
    $this->get('/profile', 'GrEduLabs\\Action\\User\\Profile')->setName('user.profile');
});
