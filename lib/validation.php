<?php

class Validator {
    public static function validateEngAlphabet($input) {
        return preg_match('/^[a-z]{1,}$/i', $input);
    }

    public static function validateName($input) {
        return preg_match('/^[a-z0-9 \-æøåöäáàéèó]{2,100}$/iu', $input);
    }

    public static function validateEmail($input) {
        return preg_match('/^[a-z0-9._%#+-]+@[a-z0-9.-]+\.[a-z]{2,}$/iu', $input);
    }

    public static function validateMobile($input) {
        return preg_match('/^[\+]{1}[0-9]{2}[\s]?[0-9]{8,14}$/', $input);
    }

    public static function validateShortDate($input) {
        return preg_match('/^[0-3][0-9]-[0-1][0-9]-[2][0][1-9][0-9]$/', $input); // From 00-00-2010 to 00-00-2099
    }
    
    public static function validateNumber($input) {
        return preg_match('/^[0-9]{1,}$/i', $input);
    }
    
    public static function validateBool($input) {
        return preg_match('/^1|0|true|false$/i', $input);
    }
    
    public static function validateSearch($input) {
        return preg_match('/^[a-z0-9 \-_@æøåöä.]{3,}$/iu', $input);
    }

    public static function validateDescription($input) {
        return preg_match('/^[a-z0-9=, \-\'æøåöä.\/]{3,}$/iu', $input);
    }

    public static function validateDomain($input) {
        return preg_match('/^[a-z.]{3,30}$/iu', $input);
    }

    public static function validateGuid($input) {
        return preg_match('/^[0-9a-z-]{36}$/i', $input);
    }
}

?>