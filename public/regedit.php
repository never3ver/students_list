<?php

require_once __DIR__ . '/../app/init.php';

$student = new Student();
$gateway = new StudentsDataGateway($pdo);
$validator = new StudentValidator($gateway);
$oldEmail = "";

if (isset($_COOKIE['name'])) {
    $student = $gateway->getStudent($_COOKIE['name']);
    $oldEmail = $student->email;
}
if (!empty($_POST)) {
    $arrayOfProperties = [
        "name",
        "secondName",
        "sex",
        "groupName",
        "email",
        "score",
        "birthYear",
        "local"
    ];
//    filling $student with $_POST contents
    foreach ($arrayOfProperties as $value) {
        if (!empty($_POST[$value])) {
            $student->$value = trim(strval($_POST[$value]));
        }
    }

    $errors = $validator->validate($student, $oldEmail);

    if (isset($_COOKIE['name'])) {
        $gateway->updateStudent($student);
        header("Refresh: 0; url = index.php");
        require_once __DIR__ . '/../templates/ok.html';
    } else {
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
