<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// http://localhost:8080/cwiki 로 접속했을 때
// app/Config/Routes.php 수정
$routes->group('cwiki', function($routes) {
    $routes->get('/', 'Wiki::index');
    $routes->get('view/(:any)', 'Wiki::view/$1');
    $routes->get('edit/(:any)', 'Wiki::edit/$1');
});