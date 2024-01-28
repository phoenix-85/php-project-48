<?php

declare(strict_types=1);

namespace Gendiff\Cli;

use Docopt;

function getInput(): void
{
    $doc = getDoc();
    $args = Docopt::handle($doc, ['version' => '1.0']);
}

function getDoc(): string
{
    return <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help                     Show this screen
  -v --version                  Show version
DOC;
}