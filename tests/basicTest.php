<?php

use \photon\config\Container as Conf;

class SessionTest extends \photon\test\TestCase
{
    protected $conf;

    public function setup()
    {
        parent::setup();
        Conf::set('session_storage', '\photon\session\storage\MongoDB');
    }

    public function testEmptySession()
    {
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
