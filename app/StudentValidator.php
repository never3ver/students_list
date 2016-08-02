<?php

class StudentValidator {

    protected $gateway;

    public function __construct($gateway) {
        $this->gateway = $gateway;
    }

    public function validate(Student $student, $oldEmail) {
        $errors = null;

        if (!preg_match("/^[A-ZА-ЯЁ]{1}[a-zа-яё`-\\s]{1,44}$/u", $student->name)) {
            $errors['name'] = "Имя может содержать латинские либо кириллические буквы, апострофы, пробелы и дефисы, первая заглавная, не более 45 символов всего.";
        }
        if (!preg_match("/^[A-ZА-ЯЁ]{1}[a-zа-яё`-\\s]{1,44}$/u", $student->secondName)) {
            $errors['secondName'] = "Фамилия может содержать латинские либо кириллические буквы, апострофы, пробелы и дефисы, первая заглавная, не более 45 символов всего.";
        }
        if (!preg_match("/[-А-ЯЁа-яёa-zA-Z0-9]{1,5}/u", $student->groupName)) {
            $errors['groupName'] = "Имя группы может содержать не более 5 латинских либо кириллических символов, цифр, дефисов";
        }
        if (!preg_match("/([a-zA-Z0-9_+.-]+)@([a-z0-9-]+.[A-Za-z]+)/ui", $student->email)) {
            $errors['email'] = "Адрес электронной почты принимается в формате name@server.com";
        }
        if (!$this->checkEmail($oldEmail, $student->email)) {
            $errors['email'] .= "Введенный email уже используется";
        }
        if ((!preg_match("/[0-9]{1,3}/", $student->score)) || $student->score > 100) {
            $errors['score'] = "Количество баллов может быть от 0 до 100";
        }
        if (!preg_match("/[19|20][0-9]{2}/", $student->birthYear)) {
            $errors['birthYear'] = "Год рождения может быть только четырехзначным и содержать только цифры";
        }
        return $errors;
    }

    protected function checkEmail($oldEmail, $email) {
        if ($this->gateway->isEmailUnique($email) || $email == $oldEmail) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getNameRegExp() {
        return "^[А-ЯA-ZЁ]{1}[а-яёa-z\s]{45}$";
    }

    public function getGroupRegExp() {
        return "[-А-ЯЁа-яёa-zA-Z0-9]{1,5}";
    }

    public function getEmailRegExp() {
        return "[a-zA-Z0-9_+.-]+)@([a-z0-9-]+.[A-Za-z]+";
    }

    public function getScoreRegExp() {
        return "[0-9]{1,3}";
    }

    public function getYearRegExp() {
        return "[19|20][0-9]{2}";
    }

}
