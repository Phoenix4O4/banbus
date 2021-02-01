<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/version.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/container.php');
$container = $containerBuilder->build();
$app = $container->get(App::class);

date_default_timezone_set($container->get('settings')['app']['timezone']);

(require __DIR__ . '/middleware.php')($app);
(require __DIR__ . '/routes.php')($app);
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
if ($container->get('settings')['basepath']) {
    $app->setBasePath($container->get('settings')['basepath']);
}
return $app;
