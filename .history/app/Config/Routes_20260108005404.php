<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// http://localhost:8080/cwiki 로 접속했을 때
$routes->get('cwiki', 'Wiki::index'); 

// http://localhost:8080/cwiki/view/test 로 접속했을 때
$routes->get('cwiki/view/(:any)', 'Wiki::view/$1');