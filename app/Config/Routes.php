<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('autosearch', 'Findsearch::autosearch');
$routes->get('findsearch/indexAllToElasticsearch', 'Findsearch::indexAllToElasticsearch');

$routes->get('/admin', 'Admin\Auth::login');
$routes->get('/admin/login', 'Admin\Auth::login');
$routes->post('/admin/loginProcess', 'Admin\Auth::loginProcess');
$routes->get('/admin/logout', 'Admin\Auth::logout');

// $routes->get('/admin/dashboard', 'Admin\Dashboard::index', ['filter' => 'auth']);
// $routes->get('/admin/setting', 'Admin\Setting::index', ['filter' => 'auth']);

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'auth'], function ($routes) {
    $routes->get('home', 'Home::index');

    // admin category modules
    // $routes->get('category', 'Category::index');
    // $routes->get('category/create-category', 'Category::create_category');
    // $routes->post('category/add-category', 'Category::add_category');

    // $routes->get('promo-code', 'Promocode::index');
});


//Frontend Routes
$routes->post('auth/register', 'Auth::register');
$routes->post('auth/login', 'Auth::login');
$routes->post('auth/forgot-password', 'Auth::forgotPassword');
$routes->get('auth/logout', 'Auth::logout');

//socail login
$routes->get('facebook/login', 'FacebookAuth::login');
$routes->get('facebook/callback', 'FacebookAuth::callback');
$routes->get('facebook/logout', 'FacebookAuth::logout');
$routes->get('google-login', 'GoogleAuth::login');
$routes->get('google-callback', 'GoogleAuth::callback');
$routes->get('apple-login', 'AppleAuth::login');
$routes->get('apple-callback', 'AppleAuth::callback');

$routes->view('thankyou', 'frontend/thankyou');
$routes->get('reset-password/(:any)', 'Auth::resetPasswordForm/$1');
$routes->post('reset-password', 'Auth::resetPasswordSubmit');

$routes->post('location/getStates', 'LocationController::getStates');
$routes->post('location/getCities', 'LocationController::getCities');

// Product Page
$routes->get('products', 'ProductController::index');
$routes->get('product/(:segment)', 'ProductController::show/$1');
$routes->get('categories', 'CategoryController::index');
// $routes->get('categories/(:segment)', 'CategoryController::sub_category/$1');
// $routes->get('categories/(:segment)/(:segment)', 'CategoryController::sub_sub_category/$1/$2');
$routes->get('brands', 'BrandController::index');
$routes->get('brands/(:segment)', 'BrandController::brand_products/$1');
$routes->get('about-zylax-computers', 'PageController::about_us');
$routes->get('contact-us', 'PageController::contact_us');
$routes->post('contact-us', 'PageController::contact_us');
$routes->post('form-submit', 'PageController::submit_page');
$routes->get('faq', 'PageController::faq');
$routes->get('404', 'ErrorController::index');
$routes->set404Override('App\Controllers\ErrorController::index');


$routes->group('', ['filter' => 'userauth'], function ($routes) {
    $routes->get('auth/my-account', 'ProfileController::index');
    $routes->post('profile/update', 'ProfileController::update');
    $routes->post('profile/uploadProfileImage', 'ProfileController::uploadProfileImage');
    $routes->post('profile/changepassword', 'ProfileController::changePasswordSubmit');

    $routes->get('user/orders', 'ProfileController::orders');
    $routes->get('user/orders/showDetails/(:segment)', 'ProfileController::showDetails/$1');
    $routes->get('user/orders/downloadInvoice/(:segment)', 'CheckoutController::downloadInvoice/$1');
    $routes->get('user/wishlists', 'ProfileController::wishlists');
    $routes->get('user/change-password', 'ProfileController::changepassword');
    $routes->get('auth/manage-address', 'ProfileController::manageaddress');
    $routes->post('profile/insert-address', 'ProfileController::insertAddress');
    $routes->get('profile/address-delete/(:segment)', 'ProfileController::deleteAddress/$1');
    $routes->post('profile/update-address', 'ProfileController::updateAddress');
});
// goolge recaptcha
$routes->post('subscribeNewsletter', 'GoogleAuth::subscribeNewsletter');
$routes->get('add-to-cart', 'CheckoutController::addToCart');
$routes->get('checkout', 'CheckoutController::checkOut');
$routes->post('guest', 'CheckoutController::guest_checkout');
// $routes->post('user_checkout', 'CheckoutController::user_checkout');
$routes->post('fetch-shipping', 'CheckoutController::fetchShippingCharges');
$routes->get('cancel', 'CheckoutController::cancel');
$routes->get('success', 'CheckoutController::success');
// $routes->post('success-paypal', 'CheckoutController::succesPaypal');
$routes->post('ipn', 'CheckoutController::ipn');
$routes->get('processNab', 'CheckoutController::processNab');
$routes->get('track-order', 'CheckoutController::trackorder');

$routes->post('paypal/create-order', 'PayPalController::createOrder');
// $routes->get('paypal/success', 'PayPalController::success');
// $routes->get('paypal/cancel', 'PayPalController::cancel');

// $routes->get('/(:any)', 'SlugController::index/$1');
$routes->get('^(?!admin|api|assets|uploads)(.*)', 'SlugController::index/$1');
