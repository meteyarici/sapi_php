<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Session\Manager;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;
use Phalcon\Session\Adapter\Redis as RedisSession;

// Initializing a DI Container
$di = new FactoryDefault();

// application-wide config
$di->setShared('config', $config);

$di->set('db', function() use ($config) {
    $dbclass = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    return new $dbclass(array(
        "host"     => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname"   => $config->database->dbname
    ));
});

$di->setShared('session',function(){
    $session = new Phalcon\Session\Manager();
    $files = new Phalcon\Session\Adapter\Stream( [
        'savePath' => '/tmp',
    ]);
    $session->setAdapter($files)->start();
    return $session;

    //TODO: why?
    @session_start();
});
/*
$di->setShared('session',function() use ($di) {
    $session = new RedisSession([
        "uniqueId"   => "app",
        "host"       => "redis",
        "port"       => 6379,
        "auth"       => "foobared",
        "persistent" => false,
        "lifetime"   => 3600,
        "prefix"     => "my",
        "index"      => 1,
    ]);
    $session->start();
    return $session;
});*/
/**
 * Overriding Response-object to set the Content-type header globally
 */
$di->setShared('response', function () {
    $response = new \Phalcon\Http\Response();
    $response->setContentType('application/json', 'UTF-8');

    return $response;
});


// Service to perform authentication-related ops
$di->setShared('authService', 'App\Services\AuthService');

return $di;