<?php

namespace Differ\Formatters\stylish;

use function Differ\Formatter\toString;

const TAB = "    ";
function formatStylish(array $data): string
{
    $iter = function ($node, $depth) use (&$iter) {
        if (!is_array($node)) {
            return toString($node);
        }

        $currentIndent = str_repeat(TAB, $depth);
        $bracketIndent = str_repeat(TAB, $depth - 1);
        $indent1 = substr_replace($currentIndent, '-', -2, 1);
        $indent2 = substr_replace($currentIndent, '+', -2, 1);

        $lines = [];
        foreach ($node as $key => $item) {
            $status = $item["status"] ?? null;
            if ($status === "removed") {
                $lines[] = "{$indent1}{$key}: " . $iter($item["value"], $depth + 1);
            } elseif ($status === "added") {
                $lines[] = "{$indent2}{$key}: " . $iter($item["value"], $depth + 1);
            } elseif ($status === "updated") {
                $lines[] = "{$indent1}{$key}: " . $iter($item["value1"], $depth + 1);
                $lines[] = "{$indent2}{$key}: " . $iter($item["value2"], $depth + 1);
            } else {
                $lines[] = "{$currentIndent}{$key}: " . $iter($item["value"], $depth + 1);
            }
        }

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };
    return $iter($data, 1);
}
