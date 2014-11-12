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
        $this->assertEquals(false, isset($res->COOKIE['sid']));
        $this->assertEquals(false, isset($res->headers['Vary']));
    }

    public function testFilledSession()
    {
        // Receive a request, and store a counter in the answer
        $req = \photon\test\HTTP::baseRequest();
        $mid = new \photon\session\Middleware();
        $this->assertEquals(false, $mid->process_request($req));

        $req->session['cpt'] = 1234;
        $res = new \photon\http\Response('Hello!');
        $mid->process_response($req, $res);
        $this->assertEquals(true, isset($res->COOKIE['sid']));

        // Save the signed cookie
        $headers = $res->getHeaders();
        $rc = preg_match('/Set-Cookie: sid=([\w\.]+);/', $headers, $sid);
        $this->assertEquals($rc, 1);
        $sid = $sid[1];

        unset($req);
        unset($res);
        unset($mid);
        
        // Reload the session
        $req = \photon\test\HTTP::baseRequest('GET', '/', '', '', array(), array('cookie' => 'sid=' . $sid));
        $mid = new \photon\session\Middleware();
        $this->assertEquals(false, $mid->process_request($req));
        $cpt = $req->session['cpt'];
        $this->assertEquals(1234, $cpt);
    }
}
