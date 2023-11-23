<?php
declare(strict_types=1);

ob_start();
session_start();

require_once("constants.php");

require_once(PROJECT_PATH . "/vendor/autoload.php");
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require_once("database.php");
require_once("functions.php");
require_once("query_functions.php");
require_once("auth_functions.php");



$db = db_connect();

header("Cache-Control: no-cache, must-revalidate");
?>
