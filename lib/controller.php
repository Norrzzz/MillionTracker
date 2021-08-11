<?php
    require 'utilities.php';
    require 'validation.php';
    require 'api.php';

    $request = '';
    if (isset($_POST['request']) && Validator::validateEngAlphabet($_POST['request'])) {
        $request = $_POST['request'];
    }

    switch ($request) {
        case 'getHolders':
            if (!isset($_POST['days']) || !Validator::validateNumber($_POST['days'])) { exitInvalidInput('Invalid days'); }
            echo outJson($BackendAPI->getHolders($_POST['days']));
            break;
        
        case 'getPrice':
            if (!isset($_POST['days']) || !Validator::validateNumber($_POST['days'])) { exitInvalidInput('Invalid days'); }
            echo outJson($BackendAPI->getPrice($_POST['days']));
            break;
        
        case 'getStats':
            echo outJson($BackendAPI->getStats(), true);
            break;
        
        case 'getRanks':
            echo outJson($BackendAPI->getRanks(), true);
            break;
        
        case 'getTopHundred':
            echo outJson($BackendAPI->getTop1000(), true);
            break;

        default:
            exitInvalidInput('Invalid operation', 5);
            break;
    }
?>