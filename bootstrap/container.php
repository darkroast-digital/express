<?php

/*
|--------------------------------------------------------------------------
| #CONTAINER
|--------------------------------------------------------------------------
*/


// #BOOT CONTAINER
// =========================================================================

$container = $app->getContainer();



// #AUTH
// =========================================================================

$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};




// #BASKET
// =========================================================================

$container['basket'] = function ($container) {
    return new \App\Basket\Basket;
};




// #URL
// =========================================================================

$container['url'] = function ($container) {
    return $container->settings['url'];
};




// #BRAINTREE
// =========================================================================

// $container['braintree'] = function ($container) {
//     $braintree_config = new Braintree_Configuration;
//     $braintree_token = new Braintree_ClientToken;

//     $braintree_config::environment('sandbox');
//     $braintree_config::merchantId('rqt73v4n4kfhyzyq');
//     $braintree_config::publicKey('prmwc62q9y2n4xqt');
//     $braintree_config::privateKey('3bb4fecabc388e29d3f45c82f6eb736a');

//     return $braintree_token;
// };

$container['braintree'] = function ($container) {
    $braintree_config = new Braintree_Configuration;
    $braintree_token = new Braintree_ClientToken;

    $braintree_config::environment(getenv('BRAINTREE_ENVIRONMENT'));
    $braintree_config::merchantId(getenv('BRAINTREE_MERCHANTID'));
    $braintree_config::publicKey(getenv('BRAINTREE_PUBLICKEY'));
    $braintree_config::privateKey(getenv('BRAINTREE_PRIVATEKY'));

    return $braintree_token;
};




// #VIEWS
// =========================================================================

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => $container->settings['views']['cache']
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
        'admin' => $container->auth->admin(),
    ]);

    $view->getEnvironment()->addGlobal('basket', $container['basket']);

    $view->getEnvironment()->addGlobal('flash', $container['flash']);


    return $view;
};



// #MAIL
// =========================================================================

$container['mail'] = function ($container) {
    $config = $container->settings['mail'];

    $mail = new PHPMailer;

    return (new App\Mail\Mailer\Mailer($mail, $container->view))->alwaysFrom($config['from']['address'], $config['from']['name']);
};



// #VALIDATION
// =========================================================================

$container['validator'] = function ($container) {
    return new App\Validation\Validator;
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));


// #OLD INPUTS
// =========================================================================

$app->add(new \App\Middleware\OldInputMiddleware($container));



// #FLASH
// =========================================================================

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};



// #CSRF
// =========================================================================

// $container['csrf'] = function ($container) {
//     return new \Slim\Csrf\Guard;
// };

// $app->add(new \App\Middleware\CsrfViewMiddleware($container));

// $app->add($container->csrf);
