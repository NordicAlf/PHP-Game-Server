<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit\Settings;

use ForestServer\DTO\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{
    protected Settings $settings;

    protected function setUp(): void
    {
        $this->settings = new Settings();
    }

    /** @dataProvider dataProvider */
    public function testSettings(array $expectedSettings): void
    {
        $settings = $this->settings
            ->setHooks()
            ->setWorkerNum(4)
            ->setTaskWorkerNum(4)
            ->setSslFiles(__DIR__ . 'ssl.crt', __DIR__ . 'ssl.key')
            ->setHttp2Protocol()
            ->toArray();

        $this->assertEquals($expectedSettings, $settings);
    }

    public static function dataProvider(): array
    {
        return [
            [
                [
                    'hook_flags' => SWOOLE_HOOK_ALL,
                    'worker_num' => 4,
                    'task_worker_num' => 4,
                    'ssl_cert_file' => __DIR__ . 'ssl.crt',
                    'ssl_key_file' => __DIR__ . 'ssl.key',
                    'open_http2_protocol' => true,
                ]
            ]
        ];
    }
}
