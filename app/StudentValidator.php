<?php

class StudentValidator {

    protected $gateway;

    public function __construct(StudentsDataGateway $gateway) {
        $this->gateway = $gateway;
    }

    public function validate(Student $student) {
        $errors = [];

        if (!preg_match("/^[A-ZА-ЯЁ][a-zA-Zа-яА-ЯЁ\\s'-]{1,45}$/u", $student->name)) {
            $errors['name'] = "Имя может содержать латинские либо кириллические буквы, апострофы, пробелы и дефисы, первая заглавная, не более 45 символов всего.";
        }
        if (!preg_match("/^[A-ZА-ЯЁ][a-zA-Zа-яА-ЯЁ\\s'-]{1,45}$/u", $student->secondName)) {
            $errors['secondName'] = "Фамилия может содержать латинские либо кириллические буквы, апострофы, пробелы и дефисы, первая заглавная, не более 45 символов всего.";
        }
        if (!preg_match("/[-А-ЯЁа-яёa-zA-Z0-9]{1,5}/u", $student->groupName)) {
            $errors['groupName'] = "Имя группы может содержать не более 5 латинских либо кириллических символов, цифр, дефисов";
        }
        if (!filter_var($student->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Адрес электронной почты принимается в формате name@server.com.";
        }
        if (!$this->gateway->isEmailUnique($student->email, $student->id)) {
            $errors['email'] .= " Введенный email уже используется";
        }
        if ((!preg_match("/[0-9]{1,3}/", $student->score)) || $student->score > 300) {
            $errors['score'] = "Количество баллов может быть от 0 до 300";
        }
        if (!preg_match("/[0-9]{4}/", $student->birthYear)) {
            $errors['birthYear'] = "Год рождения может быть только четырехзначным и содержать только цифры";
        }
        return $errors;
    }

    public function getNameRegExp() {
        return "^[А-ЯA-ZЁ][а-яёa-z\s-']{1,45}$";
    }

    public function getGroupRegExp() {
        return "[-А-ЯЁа-яёa-zA-Z0-9]{1,5}";
    }

    public function getYearRegExp() {
        return "[0-9]{4}";
    }

}
