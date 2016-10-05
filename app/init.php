<?php

require 'config.php';
$dsn = "mysql:host=$host;dbname=$db";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
];
$pdo = new PDO($dsn, $dbUser, $dbPass, $opt);

require_once __DIR__ . '/../autoload.php';

$gateway = new StudentsDataGateway($pdo);
$authorizer = new Authorization($gateway);
