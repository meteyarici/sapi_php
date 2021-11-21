<?php

use Phalcon\Mvc\Micro;
use Phalcon\Http\Response;
use Phalcon\Session\Adapter\Redis;
use Phalcon\Session\Manager;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;
use \Phalcon\Http\Request;

use App\Models\Users;
use App\Models\Orders;
use App\Models\Token;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH);

// load services
$loader = require(APP_PATH . '/config/loader.php');

// Load config
$config = require(APP_PATH . '/config/config.php');

/**
 * Prepare/init dependency injection container
 *
 * @var \Phalcon\DI\FactoryDefault $di
 */

$di = require APP_PATH . '/config/di.php';


$app = new Micro($di);


/**
 * hook: Executed before every route is executed
 */
$app->before(function () use ($app, $di) {

    $authService = $di->get('authService');
    $allowedRoutes = ['/api/authenticate', '/api/auth_fail'];
    $currentRouteName = $di->get('router')->getMatchedRoute()->getCompiledPattern();
    foreach ($allowedRoutes as $allowedRoute) {
        if ($currentRouteName == $allowedRoute) {
            return true;
        }
    }

    if (!$authService->isAuthenticated()) {

        $app->stop();
        $app->response->redirect("/api/auth_fail")->sendHeaders();

        return false;
    }

    return true;
});

/**
 * @return jwt token
 */
$app->post('/api/authenticate', function () use ($config, $di, $app) {

    $username = $this->request->getPost("username");
    $password = $this->request->getPost("password");

    $users = new Users();
    $user = $users->findFirst([
        'conditions' =>
            'username = :username: AND password = :password:',
        'bind' => [
            'username' => $username,
            'password' => $password,
        ]
    ]);

    if ($user) {

        $authService = new App\Services\AuthService();
        $jwt = $authService->authenticate([
            'username' => $user->username
        ]);

        $arr = ["auth_token" => $jwt[0]];

        $token = new Token;

        $token->token = $jwt[0];
        $token->user = $user->id;
        $token->expire = $jwt[1];

        if ($token->save() == false) {
            foreach ($token->getMessages() as $message) {
                $this->flash->error((string)$message);
            }
        }

        $response = new Response();
        $response->setJsonContent($arr);
        return $response;

    } else {
        $result = [
            'status' => 'error',
            'message' => 'Authentication failed, invalid credentials!'
        ];
        $response = new Response();
        $response->setJsonContent($result);
        return $response;
    }
});

/**
 * @return json
 */
$app->post('/api/orders/create', function () use ($config, $di, $app) {

    $order = new Orders();

    $request = new Request();
    $authHeader = $request->getHeader('Authorization');

    $ownerToken = explode(" ", $authHeader);

    $tokenModel = new Token;
    $owner = $tokenModel->findFirst([
        'conditions' =>
            'token = :token:',
        'bind' => [
            'token' => $ownerToken[1],

        ]
    ]);

    $order->orderCode = $this->request->getPost("orderCode");
    $order->productid = $this->request->getPost("productid");
    $order->address = $this->request->getPost("address");
    $order->quantity = $this->request->getPost("quantity");
    $order->shippingDate = $this->request->getPost("shippingDate");
    $order->owner = $owner->user;

    if ($order->save() == false) {
        foreach ($order->getMessages() as $message) {
            $this->flash->error((string)$message);
        }
    } else {
        $result = [
            'status' => 'success',
            'message' => 'Order Created '
        ];
        $response = new Response();
        $response->setJsonContent($result);
        return $response;
    }

});

/**
 * @return json
 */
$app->post('/api/orders/update', function () use ($config, $di, $app) {

    $orderCode = $this->request->getPost("orderCode");

    $orders = new Orders();

    $order = $orders->findFirst([
        'conditions' =>
            'orderCode = :orderCode: AND owner = :owner:',
        'bind' => [
            'orderCode' => $orderCode,
            'owner' => 1,
        ]
    ]);

    $date = new \DateTime();
    if ($order->shippingDate < $date->getTimestamp()) {
        $result = [
            'status' => 'error',
            'message' => 'Order time has passed!'
        ];
        $response = new Response();
        $response->setJsonContent($result);
        return $response;
    }

    if ($order) {
        $order->productid = $this->request->getPost("productid");
        $order->address = $this->request->getPost("address");
        $order->quantity = $this->request->getPost("quantity");
        $order->shippingDate = $this->request->getPost("shippingDate");


        if ($order->update() == false) {
            foreach ($order->getMessages() as $message) {
                $this->flash->error((string)$message);
            }
        } else {
            $result = [
                'status' => 'success',
                'message' => 'Order Updated '
            ];
            $response = new Response();
            $response->setJsonContent($result);
            return $response;
        }
    } else {
        $result = [
            'status' => 'error',
            'message' => 'Invalid order code '
        ];
        $response = new Response();
        $response->setJsonContent($result);
        return $response;
    }


});

/**
 * @return json
 */
$app->get(
    '/api/orders/list',
    function () use ($app) {

        $orders = new Orders();
        $list = $orders->find([
            'conditions' => 'owner = :owner:',
            'bind' => [
                'owner' => '1',
            ]
        ]);

        $response = new Response();
        $response->setJsonContent($list);
        return $response;

    }
);

/**
 * @return json
 */
$app->get(
    '/api/orders/detail/{orderCode}',
    function ($orderCode) use ($app) {
        $orders = new Orders();
        $list = $orders->find([
            'conditions' => 'orderCode = :orderCode: AND owner = :owner:',
            'bind' => [
                'orderCode' => $orderCode,
                'owner' => '1',
            ]
        ]);
        $response = new Response();
        $response->setJsonContent($list);
        return $response;
    }
);

/**
 * @return json
 */
$app->get('/api/auth_fail', function () use ($config, $di) {
    $result = [
        'status' => 'error',
        'message' => 'Authentication failed!'
    ];
    $response = new Response();
    $response->setJsonContent($result);
    return $response;
});

$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, 'Not Found');
        $app->response->sendHeaders();

        $message = 'Nothing here...!';
        $app->response->setContent($message);
        $app->response->send();
    }
);

$app->handle(
    $_SERVER["REQUEST_URI"]
);