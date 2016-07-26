<?php

require_once __DIR__ . '/../app/init.php';

$student = new Student();
$gateway = new StudentsDataGateway($pdo);
$authorizer = new Authorization($gateway);

if (isset($_COOKIE['name'])) {
    $student = $gateway->getStudent($_COOKIE['name']);
}

if (!empty($_POST)) {
    $student->name = trim(strval($_POST['name']));
    $student->secondName = trim(strval($_POST['secondName']));
    strval($_POST['sex']) == "M" ? $student->sex = "M" : $student->sex = "F";
    $student->groupName = trim(strval($_POST['groupName']));
    $student->email = trim(strval($_POST['email']));
    $student->score = trim(strval($_POST['score']));
    $student->birthYear = trim(strval($_POST['birthYear']));
    strval($_POST['local']) === "Y" ? $student->local = "Y" : $student->local = "N";
}

$validator = new StudentValidator($gateway);

if (isset($_COOKIE['name'])) {
    if (!empty($_POST)) {
        $gateway->updateStudent($student);
        header("Refresh: 0; url = index.php");
        require_once __DIR__ . '/../templates/ok.html';
    }
} else {
    if (!empty($_POST)) {
        $student = new Student();
        $cookie = Helper::generateCookie();
        $student->cookie = $cookie;
        setcookie('name', $cookie, time() + 60 * 60 * 24 * 365 * 10, '/', null, false, true);
        $gateway->addStudent($student);
        header("Refresh: 0; url = index.php");
        require_once __DIR__ . '/../templates/ok.html';
    }
}
require_once __DIR__ . '/../templates/editForm.html';
