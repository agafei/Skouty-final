<?php
require_once("../private/initialize.php");

$jumbotron_title = "We need to first know what kind of customer are you to cater you the best services!";
$jumbotron_subtitle = "<div class=\"my-5\"><a class=\"btn btn-lg btn-light px-5 py-2 rounded-pill\" href=\"/driver/login\">I Drive🚗😎✨</a>
<a class=\"btn btn-lg btn-light px-5 py-2 rounded-pill\"  href=\"/customer/login\">I Hire🤝💸✨</a></div>";

if (is_logged_in()) {
    if ($_SESSION["user_type"] == "CUSTOMER_TYPE") {
        redirect("/customer");
    } else {
        redirect("/driver");
    }
}
include_once(INCLUDES_PATH . "/header.php");
include_once(INCLUDES_PATH . "/jumbotron.php");
?>

<p></p>
