<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

const FIXTURES_DIR = './tests/fixtures/';
class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $actual = genDiff(FIXTURES_DIR . "file1.json", FIXTURES_DIR . "file2.json", "stylish");
        $expected = file_get_contents(FIXTURES_DIR . "test_diff_stylish.txt");
        $this->assertEquals($actual, $expected);

        $actual2 = genDiff(FIXTURES_DIR . "file1.yml", FIXTURES_DIR . "file2.yml", "plain");
        $expected2 = file_get_contents(FIXTURES_DIR . "test_diff_plain.txt");
        $this->assertEquals($actual2, $expected2);
    }
}
