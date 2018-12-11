<?php

require __DIR__.'/../app/macros.php';

$uris = [];

foreach (['module', 'controller', 'action'] as $key => $uri) {
    $uris[$uri] = Request::segment($key + 1, 'index');
}

// 初始化所有模块
Module::allWithDetails();

$path = Request::path();

if (strpos($path, 'caldav') === 0) {
    App\DAV::caldav('caldav');
}

if (strpos($path, 'calendar/caldav') === 0) {
    App\DAV::caldav('calendar/caldav');
}

if (strpos($path, 'common') === 0) {
    app('Aike\Index\Controllers\ApiController')->commonAction();
}

// 首字母大写
$controller = studly_case($uris['controller']);

$action = 'Aike\\'.ucfirst($uris['module']).'\\Controllers\\'.$controller.'Controller@'.$uris['action'].'Action';

$method = Request::method();

if ($method == 'GET') {
    Route::get($path, $action);
}

if ($method == 'POST') {
    Route::post($path, $action);
}

View::addLocation(resource_path('views'));
View::addLocation(base_path('addons/'.ucfirst(Request::module()).'/views'));
