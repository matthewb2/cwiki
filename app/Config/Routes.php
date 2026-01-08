<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// http://localhost:8080/cwiki 로 접속했을 때
// Routes.php (spark serve 환경에서 주소창에 cwiki를 강제하고 싶을 때)
$routes->group('cwiki', function($routes) {
    $routes->get('/', 'Wiki::index');
    $routes->get('view/(:any)', 'Wiki::view/$1');
    $routes->get('edit/(:any)', 'Wiki::edit/$1');
    $routes->post('save', 'Wiki::save');
});