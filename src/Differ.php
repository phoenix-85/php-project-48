<?php

namespace Differ\Differ;

use function Differ\Formatter\formatOutput;
use function Differ\Parsers\parseFile;
use function Functional\sort;

function genDiff($pathToFile1, $pathToFile2, $format): string
{
    $pathToFile1 = file_exists($pathToFile1) ? $pathToFile1 : __DIR__ . '/' . $pathToFile1;
    $pathToFile2 = file_exists($pathToFile2) ? $pathToFile2 : __DIR__ . '/' . $pathToFile2;

    $file1 = parseFile(file_get_contents($pathToFile1), pathinfo($pathToFile1, PATHINFO_EXTENSION));
    $file2 = parseFile(file_get_contents($pathToFile2), pathinfo($pathToFile1, PATHINFO_EXTENSION));

    $getDiff = getDiff($file1, $file2);

    return formatOutput($getDiff, $format);
}

function getDiff($data1, $data2): array
{
    $iter = function ($currValue1, $currValue2) use (&$iter) {
        $keys = sort(
            array_keys([...(array)$currValue1, ...(array)$currValue2]),
            fn($left, $right) => strcmp($left, $right),
            true
        );

        return array_reduce(
            $keys,
            function ($acc, $key) use ($currValue1, $currValue2, $iter) {
                $existsKeyInData1 = property_exists($currValue1, $key);
                $existsKeyInData2 = property_exists($currValue2, $key);

                if (($existsKeyInData1) && ($existsKeyInData2)) {
                    $isObj1 = is_object($currValue1->$key);
                    $isObj2 = is_object($currValue2->$key);
                    if (($isObj1) && ($isObj2)) {
                        $acc["  $key"] = $iter($currValue1->$key, $currValue2->$key);
                    } elseif ($currValue1->$key == $currValue2->$key) {
                        $acc["  $key"] = $currValue1->$key;
                    } else {
                        $acc["- $key"] = $isObj1 ? $iter($currValue1->$key, $currValue1->$key) : $currValue1->$key;
                        $acc["+ $key"] = $isObj2 ? $iter($currValue1->$key, $currValue2->$key) : $currValue2->$key;
                    }
                } else {
                    if ($existsKeyInData1) {
                        if (is_object($currValue1->$key)) {
                            $acc["- $key"] = $iter($currValue1->$key, $currValue1->$key);
                        } else {
                            $acc["- $key"] = $currValue1->$key;
                        }
                    }
                    if ($existsKeyInData2) {
                        if (is_object($currValue2->$key)) {
                            $acc["+ $key"] = $iter($currValue2->$key, $currValue2->$key);
                        } else {
                            $acc["+ $key"] = $currValue2->$key;
                        }
                    }
                }
                return $acc;
            },
            []
        );
    };
    return $iter($data1, $data2);
}
