<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

use BANK\Controllers\Auth\RegisterController;
use BANK\Controllers\User\UserController;
use BANK\Controllers\Account\AccountController;
use BANK\Controllers\Account\TransactionController;

// Routes
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    $this->logger->info("Main Page Route");
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/user/db', function (Request $request, Response $response, array $args) use ($app) {
    $this->logger->info("New DB User Route");
    return $this->renderer->render($response, 'db.phtml', array('container' => $app->getContainer()));
});


// API group
$app->group('/api', function () use ($app) {
  // Version group
  $app->group('/v1', function () use ($app) {
    $jwtMiddleware = $this->getContainer()->get('jwt');

    $app->post('/user/create', RegisterController::class . ':register')->add($jwtMiddleware);
    $app->post('/user/create/db', RegisterController::class . ':registerDB');
    $app->post('/user/login', UserController::class . ':login');
    $app->post('/user/reject', UserController::class . ':reject')->add($jwtMiddleware);
    $app->post('/user/device', UserController::class . ':device');

    $app->post('/account/new', AccountController::class . ':new')->add($jwtMiddleware);
    $app->post('/account/authorize', AccountController::class . ':authorize')->add($jwtMiddleware);
    $app->post('/account/get/all', AccountController::class . ':getAll')->add($jwtMiddleware);
    $app->post('/account/get/email', AccountController::class . ':getEmail')->add($jwtMiddleware);
    $app->post('/account/get/transactions', TransactionController::class . ':getAll')->add($jwtMiddleware);

  });
});
