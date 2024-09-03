<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getDataFromFile(string $filePath): object
{
    $fileFullPath = file_exists($filePath) ? $filePath : __DIR__ . '/' . $filePath;
    return parseData(file_get_contents($fileFullPath), pathinfo($fileFullPath, PATHINFO_EXTENSION));
}
function parseData(mixed $data, string $extension): object
{
    return match ($extension) {
        'json' => json_decode($data, false),
        'yml', 'yaml' => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        default => throw new \Exception('Неизвестное расширение файла')
    };
}
