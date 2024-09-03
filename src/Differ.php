<?php

namespace Differ\Differ;

use function Differ\Formatter\formatOutput;
use function Differ\Parsers\getDataFromFile;
use function Functional\sort as func_sort;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $data1 = getDataFromFile($pathToFile1);
    $data2 = getDataFromFile($pathToFile2);
    $diff = makeDiffData($data1, $data2);
    return formatOutput($diff, $format);
}
function makeDiffData(object $data1, object $data2): array
{
    $keys = func_sort(
        array_unique(array_keys(array_merge(get_object_vars($data1), get_object_vars($data2)))),
        fn($left, $right) => strcmp($left, $right),
        true
    );

    $makeNode = function ($data) use (&$makeNode) {
        if (!is_object($data)) {
            return $data;
        }

        $keys = array_keys(get_object_vars($data));
        $values = array_map(fn($key) => ["value" => $makeNode($data->$key)], $keys);

        return array_combine($keys, $values);
    };

    $makeDiffNode = function ($key, $data1, $data2) use (&$makeDiffNode, $makeNode): array {
        if (!property_exists($data1, $key)) {
            return [
                "status" => "added",
                "value" => $makeNode($data2->$key)];
        }
        if (!property_exists($data2, $key)) {
            return [
                "status" => "removed",
                "value" => $makeNode($data1->$key)];
        }
        if (is_object($data1->$key) && is_object($data2->$key)) {
            $result = [
                "status" => "matched",
                "value" => makeDiffData($data1->$key, $data2->$key)];
        } elseif ($data1->$key === $data2->$key) {
            $result = [
                "status" => "nested",
                "value" => $makeNode($data1->$key)];
        } else {
            $result = [
                "status" => "updated",
                "value1" => $makeNode($data1->$key),
                "value2" => $makeNode($data2->$key)];
        }
        return $result;
    };

    $values = array_map(fn($key) => $makeDiffNode($key, $data1, $data2), $keys);

    return array_combine($keys, $values);
}
