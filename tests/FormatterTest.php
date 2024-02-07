<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class FormatterTest extends TestCase
{
    public function testFormatStylish(): void
    {
        $actual = genDiff('src/file1.json', 'src/file2.json', 'stylish');
        $expected = file_get_contents("/fixtures/testdiff1.txt");
        $this->assertFileEquals($expected, $actual);
    }
//    public function testFormatPlain(): void
//    {
//        $this->assertFileEquals($expected, $actual);
//    }
}
