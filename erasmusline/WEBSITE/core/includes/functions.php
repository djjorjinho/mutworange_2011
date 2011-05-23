<?php

class Functions {

        public static function createRandomString() {
        $chars = "abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        srand((double) microtime() * 1000000);
        $i = 0;
        $string = '';
        while ($i <= 31) {
            $num = rand() % 59;
            $tmp = substr($chars, $num, 1);
            $string = $string . $tmp;
            $i++;
        }
        return $string;
    }
    
    

}

// EOF