mock\_slim\_client
================

Client for a Mock Slim. (for PHPUnit)

Slim frameworkにはMockがありますが、簡単にpostとかできずに不便です。
良い感じにアクセスをシミュレートして、簡単便利にします。

イマイチ良くない設計＋ダーティーな実装なので、
動作バージョンは限られると思います。

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

license
=======

MIT

see also
=======

slim framework http://www.slimframework.com/

paquettg/php-html-parser https://github.com/paquettg/php-html-parser

phpunit https://github.com/sebastianbergmann/phpunit/
