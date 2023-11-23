<?php
function log_in_user($user) {
  session_regenerate_id();
  $_SESSION["user_type"] = $user["user_type"];
  $_SESSION["user_id"] = $user["user_id"];
  $_SESSION["email"] = $user["email"];
}
function log_out_user() {
  session_regenerate_id();
  unset($_SESSION["user_type"]);
  unset($_SESSION["user_id"]);
  unset($_SESSION["email"]);
}
function is_logged_in() {
  return isset($_SESSION["user_id"]);
}

function require_login($type) {
  if ($type == CUSTOMER_TYPE) {
    if ($_SESSION["user_type"] != $type || !is_logged_in()) {redirect("/customer/login/");}
  } else if ($type == DRIVER_TYPE) {
    if ($_SESSION["user_type"] != $type || !is_logged_in()) {redirect("/driver/login/");}
  }

  
}
 ?>
