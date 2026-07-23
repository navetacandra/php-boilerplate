<?php

global $config, $db;
$driver = $config["DB_DRIVER"];

if ($driver == "sqlite") {
    $dest = "{$driver}:{$config["DB_NAME"]}";
} else {
    $dest = "{$driver}:host={$config["DB_HOST"]}:{$config["DB_HOST"]};dbname={$config["DB_NAME"]};charset=utf8mb4";
}

$db = new PDO($dest, $config["DB_USER"], $config["DB_PASS"]);
