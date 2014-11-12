<?php

// Enable native support of PHP 64 bits integer
ini_set('mongo.native_long', 1);

return array(
    // Create a list of DB available
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

    // Configure the session backend
    'session_mongodb' => array(
        'database' => 'session-db',
        'collection' => 'session',
    ),
);
