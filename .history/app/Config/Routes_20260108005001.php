<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// app/Config/Routes.php

// 1. localhost:8080/cwiki 접속 시 Wiki 컨트롤러의 index 호출
$routes->get('cwiki', 'Wiki::index');

// 2. 하위 경로 설정 (보기, 편집 등)
$routes->get('cwiki/view/(:any)', 'Wiki::view/$1');
$routes->get('cwiki/edit/(:any)', 'Wiki::edit/$1');
$routes->post('cwiki/save', 'Wiki::save'); // 저장 로직용 (추후 구현)