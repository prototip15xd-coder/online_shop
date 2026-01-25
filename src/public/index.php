<?php

$autoload = function(string $className) {
    $path = "../Core/$className.php";
    $path1 = "../Controller/$className.php";
    $path2 = "../Model/$className.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    } elseif (file_exists($path1)) {
        require_once $path1;
        return true;
    } elseif (file_exists($path2)) {
        require_once $path2;
        return true;
    }
    return false;
};

spl_autoload_register($autoload);

$app = new App();
$app->Run();