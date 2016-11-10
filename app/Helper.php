<?php

class Helper {

    public static function highlightText($word, $text) {
        if (!$word) {
            return htmlspecialchars($text);
        } else {
            $word = htmlspecialchars($word, ENT_QUOTES);
            $word = preg_quote($word);
            $text = htmlspecialchars($text, ENT_QUOTES);
            if (preg_match("/$word/ui", $text, $matches)) {
                $text = preg_replace("/$word/ui", '<mark>' . $matches[0] . '</mark>', $text);
            }
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

    public static function getSymbolOfOrder($order) {
        if ($order == 'ASC') {
            return '&dArr;';
        } elseif ($order == 'DESC') {
            return '&uArr;';
        } elseif ($order == '') {
            return '&#8661;';
        }
    }

    public static function getPlaceholder() {
        if (isset($_GET['search']) && $_GET['search'] != '') {
            return htmlspecialchars(trim(strval($_GET['search'])));
        }
        return "Поиск";
    }

}
