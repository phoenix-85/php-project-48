<?php

$autoloadPath1 = __DIR__ . '/../../../Autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    // @phpstan-ignore-next-line
    require_once $autoloadPath1;
} else {
    // @phpstan-ignore-next-line
    require_once $autoloadPath2;
}
