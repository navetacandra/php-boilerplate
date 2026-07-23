<?php

// Block HTTP request
if(isset($_SERVER["REQUEST_URI"]))
{
    header($_SERVER["SERVER_PROTOCOL"] . " 500 Server Error", true, 500);
    die;
}

require_once "config.php";
require_once "database.php";

global $db;
$migration_file = "./migration.sql";

if(!file_exists($migration_file))
{
    echo "There is no migration.sql in current project.\n";
    die;
}

$migration_query = file_get_contents($migration_file);
$db->exec($migration_query);
