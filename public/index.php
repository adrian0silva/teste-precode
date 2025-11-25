<?php
require __DIR__ . '/../app/bootstrap.php';

$app = new App();

$router = $app->getRouter();

require APP_PATH . '/routes.php';

$app->run();
