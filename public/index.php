<?php
require __DIR__ . '/../app/bootstrap.php';

$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = $url === '' ? [] : explode('/', $url);

$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'ProdutoController';
$action = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

$controller = new $controllerName();

call_user_func_array([$controller, $action], $params);