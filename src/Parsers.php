<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile($data, $extension): object
{
    return match ($extension) {
        'json' => json_decode($data),
        'yml', 'yaml' => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        default => throw new \Exception('Неизвестное расширение файла')
    };
}
