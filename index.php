<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

$router = new Router();

$router->add('GET',  '/',                   'HomeController@index');
$router->add('GET',  '/home',               'HomeController@index');
$router->add('GET',  '/login',              'AuthController@showLogin');
$router->add('POST', '/login/process',      'AuthController@processLogin');
$router->add('GET',  '/register',           'AuthController@showRegister');
$router->add('POST', '/register/process',   'AuthController@processRegister');
$router->add('GET',  '/logout',             'AuthController@logout');

$router->add('GET',  '/map',                'MapController@index');

$router->add('GET',  '/chatbot',            'ChatbotController@index');
$router->add('POST', '/chatbot/chat',       'ChatbotController@chat');

$router->add('GET',  '/requests/create',    'RequestController@create');
$router->add('POST', '/requests/save',      'RequestController@save');
$router->add('GET',  '/requests/list',      'RequestController@list');
$router->add('GET',  '/requests/show',      'RequestController@show');

$router->add('GET',  '/donations/accept',   'DonationController@accept');
$router->add('POST', '/donations/save',     'DonationController@save');
$router->add('GET',  '/donations/history',  'DonationController@history');

$router->add('GET',  '/donor/search',       'DonorMatchController@search');

$router->add('GET',  '/screener',           'ScreenerController@index');
$router->add('POST', '/screener/check',     'ScreenerController@check');

$router->add('GET',  '/donor/history',      'DonorController@history');
$router->add('POST', '/donor/status/toggle','DonorController@toggleStatus');
$router->add('GET',  '/donor/badges',       'DonorController@badges');
$router->add('GET',  '/donor/followup',     'DonorController@followup');
$router->add('POST', '/donor/followup/save','DonorController@saveFollowup');

$router->add('GET',  '/admin/inventory',         'AdminController@inventory');
$router->add('POST', '/admin/inventory/update',  'AdminController@updateInventory');
$router->add('POST', '/admin/inventory/add',     'AdminController@addInventory');

$router->add('GET',  '/certificate/view',   'CertificateController@view');

$router->add('GET',  '/leaderboard/district','LeaderboardController@district');

$router->add('GET',  '/notifications',      'NotificationController@list');
$router->add('POST', '/notifications/read', 'NotificationController@markRead');

$router->add('GET',  '/payment/checkout',   'PaymentController@checkout');
$router->add('POST', '/payment/process',    'PaymentController@process');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
