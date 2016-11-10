<?php

require 'config.php';
$dsn = "mysql:host=$host;dbname=$db";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
];
$pdo = new PDO($dsn, $dbUser, $dbPass, $opt);

//require_once __DIR__ . '/../autoload.php';

spl_autoload_register(function ($pClassName) {
    $path = __DIR__ . "/" . $pClassName . ".php";
    if (file_exists($path)) {
        require_once ($path);
    }
});

$gateway = new StudentsDataGateway($pdo);
$authorizer = new Authorization($gateway);
