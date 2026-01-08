<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Wiki::index');
$routes->get('view/(:any)', 'Wiki::view/$1');
$routes->get('edit/(:any)', 'Wiki::edit/$1');