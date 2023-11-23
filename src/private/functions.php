<?php
function redirect($location) {
  header("Location: " . $location);
  exit;
}
function is_blank($value) {
  return !isset($value) || trim($value) === "";
}
function is_post_request() {
  return $_SERVER["REQUEST_METHOD"] == "POST";
}
function is_get_request() {
  return $_SERVER["REQUEST_METHOD"] == "GET";
}
function is_current_path($path) {
  if (substr($path, -1) == "/") $path = substr($path, 0, -1);
  return strpos(str_replace("\\", "/", getcwd()), $path) != false;
}
?>
