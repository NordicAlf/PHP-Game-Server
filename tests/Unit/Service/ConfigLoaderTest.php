<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit\Service;

use ForestServer\Service\Config\ConfigLoader;
use PHPUnit\Framework\TestCase;

class ConfigLoaderTest extends TestCase
{
    private ConfigLoader $configLoader;

    public function setUp(): void
    {
        $this->configLoader = new ConfigLoader();
    }

    public function testLoadConfig(): void
    {
        $config = $this->configLoader->load();

        self::assertNotNull($config);
    }
}
