<?php
declare(strict_types=1);

namespace ForestServer\Service\Config;

use Symfony\Component\Yaml\Yaml;

class ConfigLoader
{
    public static function load(): array
    {
        return [
            'config' => [
                'processor' => Yaml::parseFile(realpath(__DIR__ . '/../../Config/Processor.yaml'))
            ],
        ];
    }
}