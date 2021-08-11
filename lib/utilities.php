<?php
    function outJson($input, $noArray = FALSE) {
        if (!is_array($input) && !$noArray) {
            $input = array($input);
        }
        return json_encode($input);
    }
    
    function exitInvalidInput($message, $seconds = 2) {
        sleep($seconds); // Combat brute-force attacks
        exit(outJson($message));
    }
    
    function exitError($message, $seconds = 1) {
        sleep($seconds); // Combat brute-force attacks
        exit(outJson($message));
    }
?>