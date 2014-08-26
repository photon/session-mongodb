<?php

use \photon\config\Container as Conf;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    protected $conf;

    public function setUp()
    {
        $this->conf = Conf::dump();
    }

    public function tearDown()
    {
        Conf::load($this->conf);
    }

    public function testEmptySession()
    {
        Conf::set('session_storage', '\photon\session\storage\MongoDB');
        Conf::set('secret_key', 'dummy'); // used to crypt/sign the cookies
        
        $req = \photon\test\HTTP::baseRequest();
        $mid = new \photon\session\Middleware();
        $this->assertEquals(false, $mid->process_request($req));

        $res = new \photon\http\Response('Hello!');
        $mid->process_response($req, $res);
    }

    public function testFilledSession()
    {
    }
}
