<?php

set_include_path(
    __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..'
    . PATH_SEPARATOR .
    get_include_path()
);

include 'Regitra/Util/SplClassLoader.php';

$classloader = new \Regitra\Util\SplClassLoader('Regitra');
$classloader->register();