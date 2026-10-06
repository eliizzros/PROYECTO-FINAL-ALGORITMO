<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('lang/(en|es)', 'Lang::set/$1');
$routes->get('register', 'Auth::registerForm');
$routes->post('register', 'Auth::register');