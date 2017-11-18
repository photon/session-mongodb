<?php

use \photon\config\Container as Conf;
use \photon\tests\session\sessionTestCase\SessionHighLevelTestCase;

class SessionMongoDBTest extends SessionHighLevelTestCase
{
    public function setup()
    {
        parent::setup();
        Conf::set('session_storage', '\photon\session\storage\MongoDB');
    }

    public function testCreateIndex()
    {
      \photon\session\storage\MongoDB::createIndex();
    }

    public function testChangeSessionTimeout()
    {
      \photon\session\storage\MongoDB::createIndex();

      Conf::set('session_timeout', 123);
      \photon\session\storage\MongoDB::createIndex();
    }

    public function testNoTimeoutProvied()
    {
      $config = Conf::dump();
      unset($config['session_timeout']);
      Conf::load($config);

      $this->expectException(InvalidArgumentException::class);
      \photon\session\storage\MongoDB::createIndex();
    }

    public function testNoTimeoutProvied2()
    {
      $config = Conf::dump();
      unset($config['session_timeout']);
      Conf::load($config);

      $this->expectException(InvalidArgumentException::class);
      $backend = new \photon\session\storage\MongoDB;
    }
}
