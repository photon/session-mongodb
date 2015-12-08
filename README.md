session-mongodb
===============

[![Build Status](https://travis-ci.org/photon/session-mongodb.svg?branch=master)](https://travis-ci.org/photon/session-mongodb)

MongoDB backend for session storage


Quick start
-----------

1) Add the module in your project

    composer require "photon/session-mongodb"

or for a specific version

    composer require "photon/session-mongodb:1.0.0"

2) Define a MongoDB connection in your project configuration

    'databases' => array(
        'session-db' => array(
            'engine' => '\photon\db\MongoDB',
            'server' => 'mongodb://localhost:27017/',
            'database' => 'sessions',
            'options' => array(
                'connect' => true,
            ),
        ),
    ),

3) Define the session storage backend in your project configuration, and some others session parameters

    'session_storage' => '\photon\session\storage\MongoDB',
    'session_cookie_domain' => 'www.example.com',
    'session_cookie_path' => '/',
    'session_timeout' => 4 * 3600,

4) Define the configuration of the MongoDB Session module in your project configuration

    'session_mongodb' => array(
        'database' => 'session-db',
        'collection' => 'session',
    ),

5) Enjoy !

