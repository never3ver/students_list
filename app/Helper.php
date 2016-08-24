<?php

class Helper {

    public static function highlightText($word, $text) {
        if (!$word) {
            return htmlspecialchars($text);
        } else {
            $word = preg_quote($word);
            $word = htmlspecialchars($word, ENT_QUOTES);
            $text = htmlspecialchars($text, ENT_QUOTES);
            $text = preg_replace("/$word/ui", '<mark>' . $word . '</mark>', $text);
            return $text;
        }
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
        } elseif ($order == '') {
            return '&#8661;';
        }
    }

}
