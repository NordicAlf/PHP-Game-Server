<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use ForestServer\DTO\Settings;
use ForestServer\ServerManager;

var_dump($_SERVER);

$serverClient = new ServerManager('0.0.0.0', $_SERVER['PORT'] ? (int)$_SERVER['PORT'] : 8000 , (new Settings())->setHooks());
$serverClient->startServer();
