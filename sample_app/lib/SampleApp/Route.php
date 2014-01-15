<?php
namespace SampleApp;
class Route
{
    public static function registrationRoute(\Slim\Slim $app)
    {
        $app->get('/', function () use ($app) {
            $app->render('index.php');
        });

        $app->post('/form/', function () use ($app) {
            $app->render('index.php', ['nickname'=>$_POST['nickname']]);
        });
    }
}
