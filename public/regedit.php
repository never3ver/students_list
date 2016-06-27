<?php

require_once __DIR__ . '/../app/init.php';
require_once __DIR__ . '/../autoload.php';

$student = new Student();
$gateWay = new StudentsDataGateway($pdo);

if (isset($_COOKIE['name'])) {
    $row = $gateWay->getStudent($_COOKIE['name']);
    $oldEmail = $row[5];
    require_once __DIR__ . '/../templates/editForm.html';
} else {
    require_once __DIR__ . '/../templates/registerForm.html';
}

if (isset($_POST['name']) && isset($_POST['secondname']) && isset($_POST['sex']) && isset($_POST['group']) && isset($_POST['email']) && isset($_POST['score']) && isset($_POST['birthyear']) && isset($_POST['local'])) {
    $student->name = strval($_POST['name']);
    $student->secondName = strval($_POST['secondname']);
    $student->sex = strval($_POST['sex']);
    $student->group = strval($_POST['groupname']);
    $student->email = strval($_POST['email']);
    $student->score = strval($_POST['score']);
    $student->birthYear = strval($_POST['birthyear']);
    $student->local = strval($_POST['local']);
    if (isset($_COOKIE['name'])) {
        $student->id = $row[0];
    }
}

$validator = new StudentValidator();
$errorsOfValidation = $validator->validate($student);
// if there are errors
if ($errorsOfValidation && isset($student->name)) {
    foreach ($errorsOfValidation as $value) {
        echo $value . "<br>";
    }
} else {
//    checking email
    if (isset($_COOKIE['name'])) {
        if (!$gateWay->isEmailUnique($student->email) && $oldEmail != $student->email) {
            echo 'Введенный email уже используется';
        } else {
//            updating student
            if (isset($student->name) && ($gateWay->isEmailUnique($student->email) || $oldEmail = $student->email)) {
                $gateWay->updateStudent($student);
                header("Refresh: 5; url = index.php");
                echo 'Редактирование завершено. Если перенаправление не произойдет в течении 5 секунд, <a href="index.php">Главная страница</a>.';
            }
        }
    } else {
//        registering new student
        if (isset($student->name) && $gateWay->isEmailUnique($student->email)) {
            if (!$student->cookie) {
                $cookie = Helper::generateCookie();
                $student->cookie = $cookie;
                setcookie('name', $cookie, time() + 60 * 60 * 24 * 365 * 10, '/', null, false, true);
            }
            $gateWay->addStudent($student);
            header("Refresh: 5; url = index.php");
            echo 'Вы успешно зарегистрировались! Если перенаправление не произойдет в течении 5 секунд, <a href="index.php">Главная страница</a>.';
        } elseif (isset($student->name)) {
            echo 'Введенный email уже используется';
        }
    }
}