<?php

use Framework\DI\Facade;
use Framework\Utils\Configuration\ArgvConfiguration;

$app = require __DIR__.'/bootstrap/app.php';

$app->boot();

$container = $app->getContainer();
Facade::init($container);

$argv = new ArgvConfiguration();

$app->run($argv);
