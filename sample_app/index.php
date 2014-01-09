<?php
require '../vendor/autoload.php';
require 'lib/SampleApp/Route.php';
session_start();

$app = new \Slim\Slim();
\SampleApp\Route::registrationRoute($app);
$app->run();
