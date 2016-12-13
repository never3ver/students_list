<?php

class Authorization {

    protected $gateway;
    public $cookie;

    public function __construct(StudentsDataGateway $gateway) {
        $this->gateway = $gateway;
        if (isset($_COOKIE['name'])) {
            $this->cookie = trim(strval($_COOKIE['name']));
        } else {
            $this->cookie = "";
        }
    }

    public function isStudentInDatabase() {
        if ($this->gateway->isAbiturientExisting($this->cookie)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function retrieveStudent() {
        return $this->gateway->getStudent($this->cookie);
    }

    public function generateCookie() {
        $result = null;
        $source = str_split('abcdefghijklmnopqrstuvwxyz'
                . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                . '0123456789');
        for ($i = 0; $i < 45; $i++) {
            $result .= $source[mt_rand(0, count($source) - 1)];
        }
        return $result;
    }

    public function signIn(Student $student) {
        if ($student->cookie) {
//            $student->cookie = $this->generateCookie();
            setcookie('name', $student->cookie, time() + 60 * 60 * 24 * 365 * 10, '/', null, false, true);
        }
    }

}
