<?php
namespace Uzulla;

trait MockSlimClient
{
    // PLEASE OVER RIDE this your function in use.
    public static function registrationRoute(\Slim\Slim $app)
    {
        $app->get('/', function () use ($app) {
            echo 'over ride me';
        });
    }

    // PLEASE OVER RIDE this your want
    public static function createSlim()
    {
        return new \Slim\Slim([
            // template path が conf にフルパスで保存されていると共通化できて便利
            'templates.path' => '../templates'
        ]);
    }

    // get PHPHtmlParser\Dom instance by req() response.
    public function req_dom($path = '/', $method = 'GET', $input='',$option = [])
    {
        $html = $this->req($path, $method, $input, $option);
        $dom = new \PHPHtmlParser\Dom;
        $dom->load($html);

        return $dom;
    }

    // make vitrual http request. return html(of raw body).
    public function req($path = '/', $method = 'GET', $input='',$option = [])
    {
        // $app->post() が $_POSTにfallbackするので対応
        $_POST_OLD = [];
        if ($method==='POST') {
            $_POST_OLD = $_POST;
            parse_str($input, $_POST);
        }

        // create slim mock with settings.
        \Slim\Environment::mock(array_merge([
            'REQUEST_METHOD'  => $method,
            'PATH_INFO'       => $path,
            'slim.input'      => $input,
            'SCRIPT_NAME'     => '',
            'QUERY_STRING'    => '',
            'SERVER_NAME'     => 'localhost',
            'SERVER_PORT'     => 80,
            'ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'ACCEPT_LANGUAGE' => 'ja,en;q=0.7,zh;q=0.3',
            'ACCEPT_CHARSET'  => 'UTF-8',
            'USER_AGENT'      => 'PHP UnitTest',
            'REMOTE_ADDR'     => '127.0.0.1',
            'slim.url_scheme' => 'http',
            'slim.errors'     => @fopen('php://stderr', 'w')
        ], $option));

        $app = static::createSlim();

        // \Slim\Slim::getInstance() response only FIRST MADE instance now(2014/05/27).
        // slim constructor DON'T overwrite \Slim\Slim::$apps when new slim instance
        // bellow code, force overwrite cached instance.
        // This is important when you use \Slim\Slim::getInstance().
        // (I was falling in hole, when use Class controllers(slim>=2.4.0))
        $app->setName('default');

        // registration route to slim.
        static::registrationRoute($app);

        ob_start();
        $app->run();
        if ($method==='POST') {
            $_POST = $_POST_OLD;
        }

        return ob_get_clean();
    }
}
