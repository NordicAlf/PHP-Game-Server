<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use ForestServer\DTO\Settings;
use ForestServer\ServerManager;

$serverSettings = (new Settings())
    ->setHooks()
    ->setHeartbeatIdleTime(1200)
    ->setHeartbeatCheckInterval(300);

$serverClient = new ServerManager('0.0.0.0', 8000, $serverSettings);
$serverClient->startServer();
