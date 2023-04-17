<?php

spl_autoload_extensions('.php');
spl_autoload_register();

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', __DIR__ . DIRECTORY_SEPARATOR . $class) . '.php';
    if (file_exists($class)) {
        require_once($class);
        return true;
    } return false;
});