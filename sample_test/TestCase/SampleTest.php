<?php
class SampleTest extends \PHPUnit_Framework_TestCase
{
    use \Uzulla\MockSlimClient;

    public static function registrationRoute($app)
    {
        \SampleApp\Route::registrationRoute($app);
    }

    public static function createSlim()
    {
        return new \Slim\Slim([
            'templates.path' => __DIR__.'/../../sample_app/templates'
        ]);
    }

    public function testTitle()
    {
        $dom = $this->req_dom('/');
        $this->assertEquals('sample app', $dom->find('title', 0)->text);
    }

    public function testPostNickname()
    {
        $input = http_build_query([
            'nickname'=>'test name'
        ]);
        $dom = $this->req_dom('/form', 'POST', $input);
        $this->assertEquals('test name', $dom->find('span.preview-name', 0)->text);
    }

}
