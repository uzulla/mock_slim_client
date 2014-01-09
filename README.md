mock\_slim\_client
================

Client for a Mock Slim. (for PHPUnit)

Slim frameworkにはMockがありますが、POSTをエミュレートしたりするには比較的複雑な手順を踏む必要があります。
これでは、自動テストに使いづらいので良い感じにアクセスをシミュレートするようにしました。

`req()`でhtmlを取得できます。また、`req_dom()`をつかうと`php-html-parser`インスタンスを返しますので、別途でパースせずに検証に使いやすいと思います。

実際の使い方はexampleと、サンプルコードを見てください。


注意
---

元来SlimのMockはあまりこのような用途を想定していないのか、特にPOST時のデータの扱いが微妙ですが、そこはなんとかソレっぽく動作するようにしてあります。

結果として、今後のSlimの設計変更によっては動作しなくなるかも知れません。その場合ISSUEなどで報告をお待ちしております。

install
=======

use Composer 

```
{
    "require": {
        "uzulla/mockslimclient": "dev-master"
    }
}
```

example
=======

in PHPUnit test

```
<?php
class myTest extends \PHPUnit_Framework_TestCase
{
    use \Uzulla\MockSlimClient; // use by trait

    // over ride \Uzulla\MockSlimClient::registrationRoute()
    static function registrationRoute($app)
    {
        $app->get('/', function() use ($app){
            //...
        });
        // or \myApp::registrationRoute($app);
    }
    
    // over ride \Uzulla\MockSlimClient::createSlim()
    static function createSlim()
    {
        return new \Slim\Slim([
            'templates.path' => __DIR__.'/../../sample_app/templates'
        ]);
    }    

    // sample test case.	
    public function testConfirmPost()
    {
        // get http://dummy/ html.
        $raw_html = $this->req('/');
        
        // get paquettg/php-html-parser instance
        $dom = $this->req_dom('/post/form');
        $this->assertTrue(!!$dom->find('input[name=name]'));

        // get CSRF token.
        $this->req('/');
        $csrf_token = $_SESSION['csrf_token'];

        $test_name = 'testname';
        $test_body = 'testbody';

        // build post data.
        $input = http_build_query([
            'nickname'=>$test_name,
            'body'=>$test_body,
            'csrf_token'=>$csrf_token
        ]);

        // do post.
        $dom = $this->req_dom('/post/confirm', 'POST', $input);
        $this->assertEquals($test_name, $dom->find('div.nickname-preview', 0)->text);
        $this->assertEquals($test_name, $dom->find('input[name=nickname]', 0)->value);
    }
}
```

use sample code
============

check sample webapp.
--------------------

```
$ cd sample_app
$ php -S localhost:5000
```

open http://localhost:5000/


execute sample phpunit test
----------------------------

```
$ cd sample_test
$ php ../vendor/bin/phpunit 
PHPUnit 3.7.28 by Sebastian Bergmann.

Configuration read from /Users/uzulla/dev/mock_slim_client/sample_test/phpunit.xml

..

Time: 91 ms, Memory: 4.50Mb

OK (2 tests, 2 assertions)
```


license
=======

MIT

see also
=======

- slim framework [http://www.slimframework.com/](http://www.slimframework.com/)
- paquettg/php-html-parser [https://github.com/paquettg/php-html-parser](https://github.com/paquettg/php-html-parser)
- phpunit [https://github.com/sebastianbergmann/phpunit/](https://github.com/sebastianbergmann/phpunit/)
