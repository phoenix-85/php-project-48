<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Parsers\parseFile;

class ParsersTest extends TestCase
{
    public function testParserFileJSON(): void
    {
        $filepath = __DIR__ . '/fixtures/file1.json';

        $actual = parseFile($filepath);
        $expected = json_decode($filepath);

        $this->assertEquals($expected, $actual);
    }
    public function testParserFileYAML(): void
    {
        $filepath = __DIR__ . '/fixtures/file1.yml';

        $actual = parseFile($filepath);
        $expected = ['host' => 'hexlet.io', 'timeout' => 50, 'proxy' => '123.234.53.22', 'follow' => false];

        $this->assertEquals($expected, $actual);
    }
}
