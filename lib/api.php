<?php

    class DB {
        private $dbConnection;

        function __construct($host, $database, $user, $password) {
            $this->Connect($host, $database, $user, $password);
        }

        function __destruct() {
            $this->Disconnect();
        }

        private function Connect($host, $database, $user, $password) {
            $this->dbConnection = mysqli_connect($host, $user, $password, $database) or die("Unable to connect to database host");

            if ($this->dbConnection -> connect_errno) die("Failed connecting to database");
            if ($this->dbConnection === false) die("Failed connecting to database");
        }

        private function Disconnect() {
            mysqli_close($this->dbConnection);
        }

        function Query($sql, $arguments = null, $argumentTypes = null) {
            $stmt = $this->dbConnection->prepare($sql);

            if (false === $stmt) {
                die('prepare() failed: '.htmlspecialchars($this->dbConnection->error));
            }

            if ($arguments != null && $argumentTypes != null) {
                $rc = $stmt->bind_param($argumentTypes, ...$arguments);

                if (false === $rc) {
                    die('bind_param() failed: '.htmlspecialchars($stmt->error));
                }
            }
            
            $rc = $stmt->execute();

            if (false === $rc) {
                die('execute() failed: '.htmlspecialchars($stmt->error));
            }

            if (strpos($sql, 'SELECT') !== false) {
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $data;
            } else {
                $stmt->close();
                return "OK";
            }
        }
    }

    class General {

        public static function outJson($input, $noArray = FALSE) {
            if (!is_array($input) && !$noArray) {
                $input = array($input);
            }
            return json_encode($input);
        }

        public static function newHttpRequest($type = 'POST', $url, $token = NULL, $param = '{}', $contentType = 'application/json', $timeout = 0) {
            $request = curl_init();

            $headers = array(
                "Content-Type: $contentType"
            );

            if (!is_null($token)) {
                array_push($headers, "Authorization: $token");
            }

            curl_setopt_array($request, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_CUSTOMREQUEST => $type,
                CURLOPT_HTTPHEADER => $headers,
            ));

            if ($type === 'POST' || $type === 'PUT') {
                if ($contentType === 'application/json') {
                    if (!General::isJson($param)) {
                        return "Expecting param to be JSON string";
                    }
                    curl_setopt($request, CURLOPT_POSTFIELDS, $param);
                } elseif ($contentType === 'application/x-www-form-urlencoded') {
                    if (!is_array($param)) {
                        return "Expecting param to be array";
                    }
                    curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($param));
                }
            }

            $response = curl_exec($request);

            $responseStatus = curl_getinfo($request);
            
            curl_close($request);

            $codes = array(
                "400" => "Bad Request",
                "401" => "Unauthorized",
                "403" => "Forbidden",
                "404" => "Not Found",
                "500" => "Internal Server Error",
                "502" => "Bad Gateway",
                "503" => "Service Unavailable",
                "503" => "Gateway Timeout"
            );

            switch ($responseStatus["http_code"]) {
                case 400:
                case 401:
                case 403:
                case 404:
                case 500:
                case 502:
                case 503:
                case 504:
                    return array(
                        "status" => $responseStatus["http_code"],
                        "response" => $codes["".$responseStatus["http_code"]]." - ".$responseStatus["url"]." - $response"
                    );
            }
            
            if (!empty($response)) {
                return array(
                    "status" => $responseStatus["http_code"],
                    "response" => json_decode($response)
                );
            }

            return "No JSON response..";
        }

        private static function isJson($string) {
            return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
         }
    }

    class BackendAPI {
        private $DB;

        function __construct($host, $database, $user, $password) {
            $this->DB = new DB($host, $database, $user, $password);
        }

        function getHolders($lastXDays = 7) {
            $startDate = (new DateTime("- $lastXDays days"))->format("Y-m-d H:i:s");
            return $this->DB->Query("SELECT * FROM holdersv2 WHERE time > ?;", array($startDate), "s");
        }

        function getTop1000() {
            return $this->DB->Query("SELECT * FROM top1000;");
        }

        function getPrice($lastXDays = 7) {
            $startDate = (new DateTime("- $lastXDays days"))->format("Y-m-d H:i:s");
            return $this->DB->Query("SELECT * FROM price WHERE time > ?;", array($startDate), "s");
        }

        function getStats() {
            return $this->DB->Query("SELECT * FROM stats WHERE id = 1;");
        }

        function getRanks() {
            return $this->DB->Query("SELECT * FROM ranks WHERE id = 1;");
        }

        function addIP($address, $agent) {
            $res = $this->DB->Query("INSERT INTO accesslog (address, agent) VALUES (?, ?);", array($address, $agent), "ss");
            if ($res <> "OK") {
                return "INSERT failed";
            }
            return "OK";
        }
    }

    $BackendAPI = new BackendAPI("localhost", "MillionTracker", "webuser", getenv("DBPWD"));
?>