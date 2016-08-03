<?php

class Authorization {

    protected $gateway;

    public function __construct(StudentsDataGateway $gateway) {
        $this->gateway = $gateway;
    }

    public function isAuthorized($cookie) {
        $result = $this->gateway->getStudent($cookie);
        if (is_object($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    protected function generateCookie() {
        $result = null;
        $source = str_split('abcdefghijklmnopqrstuvwxyz'
                . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                . '0123456789');
        for ($i = 0; $i < 45; $i++) {
            $result .= $source[mt_rand(0, count($source) - 1)];
        }
        return $result;
    }
    
    public function logIn(Student $student){
        $student->cookie = $this->generateCookie();
        setcookie('name', $student->cookie, time() + 60 * 60 * 24 * 365 * 10, '/', null, false, true);
    }
}
