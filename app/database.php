<?php

function get_database_connection()
{
    global $config;
    $dir = __DIR__;
    $driver = $config["DB_DRIVER"];

    if ($driver == "sqlite") {
        $dest = "{$driver}:$dir/storage/{$config["DB_NAME"]}";
    } else {
        $dest = sprintf("%s:host=%s:%s;dbname=%s;charset=utf8mb4", [
            $driver,
            $config["DB_HOST"],
            $config["DB_PORT"],
            $config["DB_NAME"],
        ]);
    }

    try {
        return new PDO($dest, $config["DB_USER"], $config["DB_PASS"], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

$db = get_database_connection();
