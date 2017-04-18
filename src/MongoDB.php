<?php

namespace photon\session\storage;
use \photon\db\Connection as DB;
use \photon\config\Container as Conf;

class Exception extends \Exception {}

class MongoDB extends \photon\session\storage\Base
{
    public $key = null;

    private $db = null;
    private $database = null;
    private $collection = null;

    public function __construct()
    {
        $backendConfiguration = Conf::f('session_mongodb', array());
        foreach ($backendConfiguration as $key => $value) {
            $this->$key = $value;
        }

        if ($this->database === null || $this->collection === null) {
            throw new Exception('Configuration missing or invalid for MongoDB Session Backend');
        }

        $this->db = DB::get($this->database)->selectCollection($this->collection);
    } 

    /**
     * Given a the request object, init itself.
     *
     * @required public function init($key, $request=null) 
     *
     * @param $key Session key
     * @param $request Request object (null)
     * @return Session key
     */
    public function init($key, $request=null) 
    {
        $this->key = $key;
    }

    public function load()
    {
        if (null === $this->key) {
            $this->data = array();
            return false;
        }

        $sess = $this->db->findOne(array('_id' => $this->key));
        if (null === $sess) {
            $this->data = array();
            return false;
        }

        $this->data = $sess['d'];

        return true;
    }

    /**
     * Check if a session key already exists in the storage.
     *
     * @param $key string
     * @return bool
     */
    public function keyExists($key)
    {
        $sess = $this->db->findOne(array('_id' => $key));
        return (null !== $sess);
    }


    /**
     * Given the response object, save the data.
     *
     * The commit call must ensure that $this->key is set afterwards.
     *
     * @required public function commit($response=null) 
     */
    public function commit($response)
    {
        // Create the object to store
        if (null === $this->key) {
            $this->key = $this->getNewKey(json_encode($response->headers));
        }

        $data = array(
            '_id' => $this->key,
            'd' => $this->data,
            't' => new \MongoDB\BSON\UTCDateTime((int)(microtime(true) * 1000)),
        );

        // Update or create the session
        $this->db->replaceOne(
            array('_id' => $this->key),
            $data,
            array('upsert' => true)
        );

        return $this->key;
    }
}

