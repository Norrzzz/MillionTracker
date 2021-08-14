<?php
    require 'lib/utilities.php';
    require 'lib/validation.php';
    require 'lib/api.php';

    // Only allow POST requests
    if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
        exitError('Only POST requests are allowed');
    }
    
    // Make sure Content-Type is application/json 
    $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    if (stripos($content_type, 'application/json') === false) {
        exitError('Content-Type must be application/json');
    }
    
    // Read the input stream
    $body = file_get_contents("php://input");
    
    // Decode the JSON object
    $object = json_decode($body, true);
    
    // Throw an exception if decoding failed
    if (!is_array($object)) {
        exitError('Failed to decode JSON object');
    }

    if (array_key_exists("key", $object) && $object["key"] === getenv("MT_API_KEY")) {

        if (array_key_exists("action", $object) && $object["action"] === "holders") {
            $holders = $BackendAPI->getHolders(1);
            $first = $holders[0];
            $last = $holders[count($holders) - 1];
            $change24h = round((100 - (($first["total"] / $last["total"]) * 100)), 2);
            $changeNum = $last["total"] - $first["total"];
            $out = array(
                "totalHolders" => $last["total"],
                "ethHolders" => $last["eth"],
                "bscHolders" => $last["bsc"],
                "change24h" => $changeNum,
                "change24hpct" => $change24h,
                "whenUpdated" => $last["time"]." UTC",
                "text" => "Current holders count is ".$last["total"]." (".$last["eth"]." ETH and ".$last["bsc"]." BSC). Change last 24 hours: $changeNum (".$change24h."%)."
            );

        } elseif (array_key_exists("action", $object) && $object["action"] === "socials") {
            $socials = $BackendAPI->getSocials(1);
            $out = $socials[count($socials) - 1];

        } elseif (array_key_exists("action", $object) && $object["action"] === "price") {
            $stats = $BackendAPI->getStats();
            $price = number_format($stats[0]["price"], 2);
            $price24h = number_format($stats[0]["price1dpct"] * 100, 1);
            $out = array(
                "price" => $price,
                "price24h" => $price24h,
                "text" => "Current price is $price. Change last 24 hours: $price24h."
            );

        } elseif (array_key_exists("action", $object) && $object["action"] === "volume") {
            $stats = $BackendAPI->getStats();
            $currentVolume = number_format($stats[0]["volume1d"], 0);
            $volumeChange24H = $stats[0]["volume1dpct"] * 100;
            $out = array(
                "volume24h" => $currentVolume,
                "volume24hpct" => $volumeChange24H,
                "text" => "24h volume is $".$currentVolume." ($volumeChange24H%)."
            );

        } elseif (array_key_exists("action", $object) && $object["action"] === "top1000") {
            $top1000 = $BackendAPI->getTop1000();
            if (array_key_exists("address", $object)) {

                $address = trim(strtolower($object["address"]));

                array_walk($top1000, function (&$value,$key) {
                    $value["address"] = trim(strtolower($value["address"]));
                });

                $matchIndex = array_search($address, array_column($top1000, 'address'));

                if ($matchIndex !== FALSE) {
                    $out = $top1000[$matchIndex];
                } else {
                    $out = array("error" => "Did not find the address '$address' among top 1000 holders");
                }
            } else {
                $out = $top1000;
            }

        } elseif (array_key_exists("action", $object) && $object["action"] === "lambo") {
            $lamboFiles = glob(__DIR__.'/assets/img/lambo/*.*');
            $lamboFile = $lamboFiles[array_rand($lamboFiles)];
            $fileName = strrev(explode("/", strrev($lamboFile), 2)[0]);
            $lambo = "https://milliontoken.live/assets/img/lambo/$fileName";
            $out = array(
                "url" => $lambo
            );

        } else {
            $out = outJson("No action specified");
        }
        header('Content-Type: application/json');
        echo outJson($out);
    } else {
        exitInvalidInput('Unauthorized', 3);
    }
    
?>
    