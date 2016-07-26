<?php

require_once __DIR__ . '/../app/init.php';

$student = new Student();
$gateway = new StudentsDataGateway($pdo);

if (isset($_COOKIE['name'])) {
    $student = $gateway->getStudent($_COOKIE['name']);
    $oldEmail = $student->email;
}
require_once __DIR__ . '/../templates/editForm.html';

if (!empty($_POST)) {
    $student->name = strval($_POST['name']);
    $student->secondName = strval($_POST['secondName']);
    strval($_POST['sex']) == "M" ? $student->sex = "M" : $student->sex = "F";
    $student->groupName = strval($_POST['groupName']);
    $student->email = strval($_POST['email']);
    $student->score = strval($_POST['score']);
    $student->birthYear = strval($_POST['birthYear']);
    strval($_POST['local']) === "Y" ? $student->local = "Y" : $student->local = "N";
}

$validator = new StudentValidator($gateway);
$errorsOfValidation = $validator->validate($student);
// if there are errors
if ($errorsOfValidation && isset($student->name)) {
    foreach ($errorsOfValidation as $value) {
        echo $value . "<br>";
    }
} else {
//    checking email
    if (isset($_COOKIE['name'])) {
        if (!$gateway->isEmailUnique($student->email) && $oldEmail != $student->email) {
            echo 'Введенный email уже используется';
        } else {
//            updating student
            $gateway->updateStudent($student);
            /*            if (isset($student->name) && ($gateway->isEmailUnique($student->email) || $oldEmail = $student->email)) {
              $gateway->updateStudent($student);
              header("Refresh: 5; url = index.php");
              echo 'Редактирование завершено. Если перенаправление не произойдет в течении 5 секунд, <a href="index.php">Главная страница</a>.';
              } */
        }
    } else {
//        registering new student
        if (isset($student->name) && $gateway->isEmailUnique($student->email)) {
            if (!$student->cookie) {
                $cookie = Helper::generateCookie();
                $student->cookie = $cookie;
                setcookie('name', $cookie, time() + 60 * 60 * 24 * 365 * 10, '/', null, false, true);
            }
            $gateway->addStudent($student);
            header("Refresh: 5; url = index.php");
            echo 'Вы успешно зарегистрировались! Если перенаправление не произойдет в течении 5 секунд, <a href="index.php">Главная страница</a>.';
        } elseif (isset($student->name)) {
            echo 'Введенный email уже используется';
        }
    }
}