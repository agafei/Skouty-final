<?php
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . "/public");
define("INCLUDES_PATH", PRIVATE_PATH . "/includes");
define("CUSTOMER_TYPE", 0);
define("DRIVER_TYPE", 1);

$public_pos = stripos($_SERVER['SCRIPT_NAME'], "/public") + 7;
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_pos);

define("WWW_ROOT", $doc_root);
?>