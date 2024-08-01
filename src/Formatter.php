<?php

namespace Differ\Formatter;

use function Differ\Formatters\plain\formatPlain;
use function Differ\Formatters\stylish\formatStylish;

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
        'plain' => formatPlain($diff),
        default => formatStylish($diff)
    };
}
