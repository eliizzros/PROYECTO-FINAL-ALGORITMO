<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('lang/(en|es)', 'Lang::set/$1');
$routes->get('register', 'Auth::registerForm');
$routes->post('register', 'Auth::register');
$routes->get('activate/(:segment)', 'Auth::activate/$1');
$routes->get('login', 'Auth::loginForm');
$routes->post('login', 'Auth::login');
$routes->post('logout', 'Auth::logout');
$routes->get('profile', 'Profile::index', ['filter' => 'auth']);
$routes->get('profile/edit/confirm', 'Profile::confirmForm', ['filter' => 'auth']);
$routes->post('profile/edit/confirm', 'Profile::confirm', ['filter' => 'auth']);
$routes->get('profile/edit', 'Profile::editForm', ['filter' => 'auth']);
$routes->post('profile/edit', 'Profile::update', ['filter' => 'auth']);
$routes->get('profile/password', 'Profile::passwordForm', ['filter' => 'auth']);
$routes->post('profile/password', 'Profile::passwordUpdate', ['filter' => 'auth']);
$routes->get('profile/image', 'Profile::imageForm', ['filter' => 'auth']);
$routes->post('profile/image', 'Profile::imageUpdate', ['filter' => 'auth']);
$routes->get('profile/image/delete', 'Profile::imageDeleteForm', ['filter' => 'auth']);
$routes->post('profile/image/delete', 'Profile::imageDelete', ['filter' => 'auth']);
$routes->group('users', ['filter' => ['auth', 'admin']], static function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('new', 'Users::newForm');
    $routes->post('new', 'Users::create');
    $routes->get('(:num)', 'Users::show/$1');
    $routes->get('(:num)/edit', 'Users::editForm/$1');
    $routes->post('(:num)/edit', 'Users::update/$1');
    $routes->get('(:num)/delete', 'Users::deleteForm/$1');
    $routes->post('(:num)/delete', 'Users::delete/$1');
});

$routes->group('tasks', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Tasks::index');
    $routes->get('new', 'Tasks::newForm');
    $routes->post('new', 'Tasks::create');
    $routes->get('(:num)', 'Tasks::show/$1');
    $routes->get('(:num)/edit', 'Tasks::editForm/$1');
    $routes->post('(:num)/edit', 'Tasks::update/$1');
    $routes->get('(:num)/delete', 'Tasks::deleteForm/$1');
    $routes->post('(:num)/delete', 'Tasks::delete/$1');
});

$routes->get('login', 'Auth::loginForm');
$routes->post('login', 'Auth::login');

$routes->get('forgot', 'Auth::forgotForm');
$routes->post('forgot', 'Auth::forgot');
$routes->get('reset/(:segment)', 'Auth::resetForm/$1');
$routes->post('reset/(:segment)', 'Auth::reset/$1');