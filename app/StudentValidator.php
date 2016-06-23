<?php

class StudentValidator
{

    public function validate(Student $student)
    {
        $errors = null;
        
        if (! preg_match("/^[A-ZА-ЯЁ][a-zа-яё`-]{1,44}/u", $student->name)) {
            $errors['name'] = "Имя может содержать латинские либо кириллические буквы, апострофы, пробелы и дефисы, первая заглавная, не более 45 символов всего.";
        }
        if (! preg_match("/^[A-ZА-ЯЁ][a-zа-яё`-]{1,44}/u", $student->secondName)) {
            $errors['secondName'] = "Фамилия может содержать латинские либо кириллические буквы, апострофы, пробелы и дефисы, первая заглавная, не более 45 символов всего.";
        }
        if (! preg_match("/M|F/", $student->sex)) {
            $errors['sex'] = "Пол может обозначаться только латинскими символами M или F";
        }
        if (! preg_match("/[-А-ЯЁа-яёa-zA-Z0-9]{1,10}/u", $student->group)) {
            $errors['group'] = "Имя группы может содержать не более 10 латинских либо кириллических символов, цифр, дефисов";
        }
        if (! preg_match("/([a-zA-Z0-9_+.-]+)@([a-z.-]+)/ui", $student->email)) {
            $errors['email'] = "Адрес электронной почты принимается в формате name@server.com";
        }
        if ((! preg_match("/[0-9]{1,3}/", $student->score)) && $student->score <= 100) {
            $errors['score'] = "Количество баллов может быть от 0 до 100";
        }
        if (! preg_match("/[0-9]{4}/", $student->birthYear)) {
            $errors['birthYear'] = "Год рождения может быть только четырехзначным и содержать только цифры";
        }
        if (! preg_match("/Y|N/", $student->local)) {
            $errors['local'] = "Территориальная принадлежность может выражаться только латинскими символами Y или N";
        }
        return $errors;
    }
}