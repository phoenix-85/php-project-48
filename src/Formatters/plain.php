<?php

namespace Differ\Formatters\plain;

// @phpstan-ignore missingType.iterableValue
function formatPlain(array $data): string
{
    $iter = function ($node, $path = "") use (&$make) {
        /** @phpstan-ignore callable.nonCallable */
        $result = array_map(fn($key, $body) => $make($key, $body, $path), array_keys($node), $node);

        return implode(PHP_EOL, array_filter($result));
    };

    $make = function ($key, $body, $keys = "") use ($iter) {
        $path = ($keys == "") ? "$key" : "{$keys}.{$key}";
        $status = $body["status"] ?? null;

        if ($status == "matched") {
            return $iter($body["value"], $path);
        } elseif ($status == "added") {
            $message = "was added with value: " . stringify($body["value"]);
        } elseif ($status == "removed") {
            $message = "was removed";
        } elseif ($status == "updated") {
            $message = "was updated. From " . stringify($body["value1"]) . " to " . stringify($body["value2"]);
        } else {
            return "";
        }
        return "Property '$path' $message";
    };
    return $iter($data);
}
function stringify(mixed $data): string
{
    if (is_array($data)) {
        return '[complex value]';
    }

    if (is_null($data)) {
        return 'null';
    }

    return var_export($data, true);
}
