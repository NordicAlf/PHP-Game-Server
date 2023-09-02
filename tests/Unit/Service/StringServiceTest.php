<?php
declare(strict_types=1);

namespace ForestServer\Tests\Unit\Service;

use ForestServer\Service\Converter\StringService;
use PHPUnit\Framework\TestCase;

class StringServiceTest extends TestCase
{
    /** @dataProvider dataProvider */
    public function testConvertCamelToSnakeCase(string $value, string $expected): void
    {
        $this->assertEquals($expected, StringService::convertCamelToSnakeCase($value));
    }

    public static function dataProvider(): array
    {
        return [
            ['iAmPropertyName', 'i_am_property_name'],
            ['IAmPropertyName', 'i_am_property_name'],
            ['property', 'property'],
            ['', ''],
        ];
    }
}
