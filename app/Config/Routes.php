<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// http://localhost:8080/cwiki 로 접속했을 때
// Routes.php (spark serve 환경에서 주소창에 cwiki를 강제하고 싶을 때)
$routes->get('/', 'Wiki::index');
$routes->get('cwiki/', 'Wiki::index');
$routes->get('view/([^/]+)', 'Wiki::view/$1');
$routes->get('edit/([^/]+)', 'Wiki::edit/$1');
$routes->post('save', 'Wiki::save');
$routes->get('history/([^/]+)', 'Wiki::history/$1');
$routes->get('revision/(:num)', 'Wiki::viewRevision/$1');
$routes->get('search', 'Wiki::search');
// 기존 라우트 설정들 사이에 추가
$routes->get('random', 'Wiki::random');
$routes->get('recent', 'Wiki::recent');
$routes->get('login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('register', 'Auth::register');
$routes->post('auth/store', 'Auth::store');
$routes->get('auth/profile', 'Auth::profile');
$routes->post('auth/update', 'Auth::update');
$routes->post('wiki/upload', 'Wiki::upload'); // Wiki 컨트롤러의 upload 메서드 연결
