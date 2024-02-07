<?php

namespace Differ\Formatter;

function formatOutput(array $diff, $format): string
{
    return match ($format) {
        'plain' => formatPlain($diff),
        default => formatStylish($diff)
    };
}

function formatStylish(array $data): string
{
    var_dump($data);
    $iter = function ($currentValue, $depth) use (&$iter) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $currentIndent = str_repeat('  ', 2 * $depth - 1);
        $bracketIndent = str_repeat('  ', 2 * $depth - 2);

        $lines = array_map(
            function ($key, $val) use ($currentIndent, $iter, $depth) {
                $prefix = substr($key, 0, 2);
                $key = substr($key, 2);

                return "{$currentIndent}{$prefix}{$key}: {$iter($val, $depth + 1)}";
            },
            array_keys($currentValue),
            $currentValue
        );

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($data, 1);
}

function formatPlain($data): string
{
    return "PLAIN";
}

function toString($value): string
{
    return match ($value) {
        true => "true",
        false => "false",
        null => "null",
        default => $value
    };
}
