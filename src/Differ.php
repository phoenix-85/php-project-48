<?php

namespace Differ\Differ;

use function Differ\Parsers\parseFile;
use function Functional\sort;

function genDiff($pathToFile1, $pathToFile2): string
{
    $pathToFile1 = file_exists($pathToFile1) ? $pathToFile1 : __DIR__ . '/' . $pathToFile1;
    $pathToFile2 = file_exists($pathToFile2) ? $pathToFile2 : __DIR__ . '/' . $pathToFile2;

    $file1 = changeBooleanToStringValue(parseFile($pathToFile1));
    $file2 = changeBooleanToStringValue(parseFile($pathToFile2));
//    $file1 = parseFile($pathToFile1);
//    $file2 = parseFile($pathToFile2);

    $keys = sort(array_keys([...(array)$file1, ...(array)$file2]), fn ($left, $right) => strcmp($left, $right), true);

    $result = array_reduce(
        $keys,
        function ($acc, $key) use ($file1, $file2) {
            $existsKeyInFile1 = property_exists($file1, $key);
            $existsKeyInFile2 = property_exists($file2, $key);

            if (($existsKeyInFile1) && ($existsKeyInFile2)) {
                if ($file1->$key == $file2->$key) {
                    $acc[] = "    $key: {$file1->$key}";
                } else {
                    $acc[] = "  - $key: {$file1->$key}";
                    $acc[] = "  + $key: {$file2->$key}";
                }
            } else {
                if ($existsKeyInFile1) {
                    $acc[] = "  - $key: {$file1->$key}";
                }
                if ($existsKeyInFile2) {
                    $acc[] = "  + $key: {$file2->$key}";
                }
            }

            return $acc;
        },
        []
    );

    $result = ['{', ...$result, '}'];

    return implode(PHP_EOL, $result);
}

function changeBooleanToStringValue($data): object
{
    var_dump($data);
    $result = new \stdClass();
    foreach ($data as $key => $value) {
        match ($value) {
            true => $result->$key = "true",
            false => $result->$key = "false",
            default => $result->$key = $value
        };
    }
    return $result;
}
