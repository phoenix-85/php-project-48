<?php

namespace Differ\Formatter;

use function Differ\Formatters\plain\formatPlain;
use function Differ\Formatters\stylish\formatStylish;
use function Differ\Formatters\json\formatJSON;

function toString($value): string
{
    return match ($value) {
        true => "true",
        false => "false",
        null => "null",
        default => (string)$value
    };
}

function formatOutput(array $diff, $format): string
{
    return match ($format) {
        'json' => formatJSON($diff),
        'plain' => formatPlain($diff),
        default => formatStylish($diff)
    };
}
