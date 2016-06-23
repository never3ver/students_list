<?php
spl_autoload_register(function ($pClassName) {
    $path = __DIR__ . "/app/" . $pClassName . ".php";
    if (file_exists($path)) {
        require_once ($path);
    }
});