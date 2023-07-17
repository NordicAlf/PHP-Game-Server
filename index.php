<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use ForestServer\DTO\Settings;
use ForestServer\ServerManager;

$serverClient = new ServerManager('127.0.0.1', 8000, (new Settings())->setHooks());

$serverClient->createPort(9200);
$serverClient->createPort(9201);
$serverClient->createPort(9202);

$serverClient->startServer();
