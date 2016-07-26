<?php

class Authorization {

    protected $gateway;

    public function __construct($gateway) {
        $this->gateway = $gateway;
    }

    public function isAuthorized($cookie) {
        $result = $this->gateway->getStudent($cookie);
        if (is_object($result)) {
//            var_dump($result);
            return TRUE;
        } else {
//            var_dump($result);
            return FALSE;
        }
    }

}
