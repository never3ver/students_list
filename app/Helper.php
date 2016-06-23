<?php

class Helper {

    public static function generateCookie() {
        $result = null;
        $source = str_split('abcdefghijklmnopqrstuvwxyz'
                . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                . '0123456789');
        for ($i = 0; $i < 45; $i++) {
            $result .= $source[mt_rand(0, count($source) - 1)];
        }
        return $result;
    }

    public static function highlightText($word, $text) {
        $text = preg_replace("/$word/ui", '<span style="background:yellow">' . $word . '</span>', $text);
        return $text;
    }

    public static function getOrder($order) {
        If ($order == '' || $order == 'DESC') {
            return 'ASC';
        } else {
            return 'DESC';
        }
    }

    public static function getSymbol($order) {
        if ($order == 'ASC') {
            return '&dArr;';
        } elseif ($order == 'DESC') {
            return '&uArr;';
        } elseif ($order == ''){
            return '&#8661;';
        }
    }

}
