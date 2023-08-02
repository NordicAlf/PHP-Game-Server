<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use ForestServer\Api\Request\Request;
use ForestServer\DTO\Settings;
use ForestServer\ServerManager;

$serverClient = new ServerManager('127.0.0.1', 8000, new Request(), (new Settings())->setHooks());

$serverClient->startServer();
