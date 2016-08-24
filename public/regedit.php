<?php

require_once __DIR__ . '/../app/init.php';

$student = new Student();
$validator = new StudentValidator($gateway);
$oldEmail = "";

if ($authorizer->isAuthorized()) {
    $student = $authorizer->retrieveStudent();
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

    $errors = $validator->validate($student);
    if (empty($errors)) {
        if ($authorizer->isAuthorized()) {
            $gateway->updateStudent($student);
            header("Location: index.php?notify=edited");
        } else {
            $authorizer->logIn($student);
            $gateway->addStudent($student);
            header("Location: index.php?notify=registered");
        }
    }
}
require_once __DIR__ . '/../templates/editForm.html';
