<?php

/*
|--------------------------------------------------------------------------
| #WEB
|--------------------------------------------------------------------------
*/




use App\Controllers\Auth\AuthController;
use App\Controllers\BasketController;
use App\Controllers\BraintreeController;
use App\Controllers\CategoryController;
use App\Controllers\CheckoutController;
use App\Controllers\ContactController;
use App\Controllers\Dashboard\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\ProductsController;
use App\Controllers\SiteController;
use App\Middleware\AuthMiddleware;



$app->get('/test', function ($request, $response, $args) {

    $mail = mail('josh@darkroast.co', 'A message from Josh Stobbs', 'This is an introductory email!');

    if (!$mail) {
        echo 'Error';
    } else {
        echo 'Success';
    }

    die;
});

// #HOME
// =========================================================================

$app->get('/', HomeController::class . ':index')->setName('home');




// #SITE
// =========================================================================

$app->get('/faqs', SiteController::class . ':faqs')->setName('faqs');
$app->get('/how-it-works', SiteController::class . ':works')->setName('works');




// #HOME
// =========================================================================

$app->get('/contact', ContactController::class . ':index')->setName('contact');
$app->post('/contact', ContactController::class . ':post');




// #CATEGORY
// =========================================================================

$app->get('/category/{slug}', CategoryController::class . ':index')->setName('category');




// #PRODUCT
// =========================================================================

$app->group('/product', function() {
    $this->get('/{slug}', ProductsController::class . ':index')->setName('product');
    $this->post('/{slug}', ProductsController::class . ':store');

    $this->get('/remove/{slug}', ProductsController::class . ':remove')->setName('product.remove');
});




// #BASKET
// =========================================================================

$app->get('/basket', BasketController::class . ':index')->setName('basket');




// #CHECKOUT
// =========================================================================

$app->get('/checkout', CheckoutController::class . ':index')->setName('checkout');
$app->post('/checkout', CheckoutController::class . ':order');
$app->get('/checkout/{hash}/summary', CheckoutController::class . ':showOrder')->setName('checkout.order.summary');
$app->get('/checkout/clear', CheckoutController::class . ':clear')->setName('checkout.clear');




// #DASHBOARD
// =========================================================================

$app->group('/dashboard', function () {
    $this->get('', DashboardController::class . ':index')->setName('dashboard.index');
})->add(new AuthMiddleware($container));





// #BRAINTREE
// =========================================================================

$app->get('/braintree/token', BraintreeController::class . ':token')->setName('braintree.token');



// #AUTHENTICATION
// =========================================================================

$app->get('/register', AuthController::class . ':getRegister')->setName('auth.register');
$app->post('/register', AuthController::class . ':postRegister');
$app->get('/login', AuthController::class . ':getLogIn')->setName('auth.login');
$app->post('/login', AuthController::class . ':postLogIn');
$app->get('/logout', AuthController::class . ':getLogOut')->setName('auth.logout');
