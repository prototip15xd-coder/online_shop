<?php

$autoload = function(string $className) {
    $className = str_replace('\\', '/', $className);
    $path = "../$className.php";
    if (file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
};

spl_autoload_register($autoload);

$app = new \Core\App();
$app->Run();