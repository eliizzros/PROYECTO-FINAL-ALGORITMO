<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('lang/(en|es)', 'Lang::set/$1');
$routes->get('register', 'Auth::registerForm');
$routes->post('register', 'Auth::register');
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
$routes->get('profile/image', 'Profile::imageForm', ['filter' => 'auth']);
$routes->post('profile/image', 'Profile::imageUpdate', ['filter' => 'auth']);
$routes->get('profile/image/delete', 'Profile::imageDeleteForm', ['filter' => 'auth']);
$routes->post('profile/image/delete', 'Profile::imageDelete', ['filter' => 'auth']);
