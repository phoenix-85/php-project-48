<?php

declare(strict_types=1);

namespace Gendiff\Cli;

use Docopt;

function getInput(): void
{
    $doc = getDoc();
    $args = Docopt::handle($doc);
}

function getDoc(): string
{
    return <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]
DOC;
}