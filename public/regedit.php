<?php

require_once __DIR__ . '/../app/init.php';

$student = new Student();
$validator = new StudentValidator($gateway);

if ($authorizer->isStudentInDatabase()) {
    $student = $authorizer->retrieveStudent();
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
        if ($student->id) {
            $gateway->updateStudent($student);
            header("Location: index.php?notify=edited");
            exit();
        } else {
            $gateway->addStudent($student);
            $authorizer->SignIn($student);
            header("Location: index.php?notify=registered");
            exit();
        }
    }
}
require_once __DIR__ . '/../templates/editForm.html';
