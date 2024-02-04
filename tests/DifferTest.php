<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $output = __DIR__. '/fixtures/output1.txt';
        $filepath1 = __DIR__ . '/fixtures/file1.json';
        $filepath2 = __DIR__ . '/fixtures/file2.json';

        file_put_contents($output, genDiff($filepath1, $filepath2));

        $expected = __DIR__ . '/fixtures/testdiff1.txt';
        $actual = __DIR__ . '/fixtures/output1.txt';

        $this->assertFileEquals($expected, $actual);
    }
}