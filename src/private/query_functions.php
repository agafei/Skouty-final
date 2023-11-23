<?php
function query($q) {
  global $db;
  return mysqli_query($db, $q);
}
function query_insert($q) {
  global $db;
  $db->query($q);
  echo $db->insert_id;
}
function multiple_query($q) {
  global $db;
  return mysqli_multi_query($db, $q);
}
function next_id_for_table($table_name) {
  return mysqli_fetch_assoc(query("SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = '$table_name' AND table_schema = DATABASE();"))["AUTO_INCREMENT"];
}
// CUSTOMER QUERIES
function add_customer($user) {
  extract($user);
  $password = password_hash($password, PASSWORD_DEFAULT);
  return query(sprintf("INSERT INTO customers (email, name, password) VALUES ('%s', '%s', '%s');", db_escape($email), db_escape($name), db_escape($password)));
}
function delete_customer_by_id($id) {
  $id = db_escape($id);
  return query("DELETE FROM customers WHERE customer_id = '$id';");
}
function delete_current_customer() {
  return delete_user_by_id($_SESSION["user_id"]);
}
function find_customer_by_id($id) {
  $id = db_escape($id);
  return query("SELECT * FROM customers WHERE customer_id = '$id';");
}
function find_customer_by_email($email) {
  $email = db_escape($email);
  return query("SELECT * FROM customers WHERE email = '$email';");
}
// DRIVER QUERIES
function add_driver($user) {
  extract($user);
  $password = password_hash($password, PASSWORD_DEFAULT);
  return query(sprintf("INSERT INTO drivers (email, name, password, vehicle, license_number, preferred_cities, driving_experience, base_rate, overtime_rate) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", db_escape($email), db_escape($name), db_escape($password), db_escape($vehicle), db_escape($license_number), db_escape($preferred_cities), db_escape($driving_experience), db_escape($base_rate), db_escape($overtime_rate)));
}
function delete_driver_by_id($id) {
  $id = db_escape($id);
  return query("DELETE FROM drivers WHERE driver_id ='$id';");
}
function delete_current_driver() {
  return delete_user_by_id($_SESSION["user_id"]);
}
function find_driver_by_id($id) {
  $id = db_escape($id);
  return query("SELECT * FROM drivers WHERE driver_id = '$id';");
}
function find_driver_by_email($email) {
  $email = db_escape($email);
  return query("SELECT * FROM drivers WHERE email = '$email';");
}
function driver_is_initialized($id) {
  $id = db_escape($id);
  return mysqli_fetch_assoc(find_driver_by_id($id))["preferred_cities"] != "";
}

// BOOKING QUERIES
function add_booking($booking) {
  extract($booking);
  $customer_id = db_escape($customer_id); $title = db_escape($title); $description = db_escape($description); $type = db_escape($type); $pickup = db_escape($pickup); $destination = db_escape($destination); $start_time = db_escape($start_time); $end_time = db_escape($end_time);
  return query("INSERT INTO bookings (customer_id, title, description, type, pickup, destination, start_time, end_time) VALUES ('$customer_id', '$title', '$description', '$type', '$pickup', '$destination', '$start_time', '$end_time');");
}
function find_all_bookings() {
  return query("SELECT * FROM bookings;");
}
function find_booking_by_id($booking_id) {
  $booking_id = db_escape($booking_id);
  return query("SELECT * FROM bookings WHERE booking_id = '$booking_id';");
}

function find_all_bookings_by_customer($customer_id) {
  $customer_id = db_escape($customer_id);
  return query("SELECT * FROM bookings WHERE customer_id = '$customer_id' ORDER BY start_time;");
}
function find_all_bookings_by_driver($driver_id) {
  $driver_id = db_escape($driver_id);
  return query("SELECT * FROM bookings WHERE driver_id = '$driver_id' ORDER BY start_time;");
}
function find_all_bookings_by_driver_preference($driver_id) {
  $driver_id = db_escape($driver_id);
  $driver = mysqli_fetch_assoc(find_driver_by_id($driver_id));
  $condition = "";
  foreach (explode(",", $driver["preferred_cities"]) as $city) {
    $condition .= "pickup LIKE '%$city%' OR ";
  }
  $condition = rtrim($condition, "OR ");
  return query("SELECT * FROM bookings WHERE " . $condition);
}
function set_booking_driver_by_id($booking_id, $driver_id) {
  $driver_id = db_escape($driver_id); $booking_id = db_escape($booking_id);
  return query("UPDATE bookings SET driver_id = '$driver_id' WHERE booking_id = '$booking_id';");
}

// MATCH QUERIES
function add_match($driver_id, $booking_id) {
  $driver_id = db_escape($driver_id); $booking_id = db_escape($booking_id);
  $customer_id = mysqli_fetch_assoc(find_booking_by_id($booking_id))["customer_id"];
  return query("INSERT INTO matches (booking_id, customer_id, driver_id) VALUES ('$booking_id', '$customer_id','$driver_id');");
}
function find_match_by_id($match_id) {
  $match_id = db_escape($match_id);
  return query("SELECT * FROM matches WHERE match_id = '$match_id';");
}
function find_all_matches_by_customer($customer_id) {
  $customer_id = db_escape($customer_id);
  return query("SELECT * FROM matches WHERE customer_id = '$customer_id';");
} 
function find_all_matches_by_booking($booking_id) {
  $booking_id = db_escape($booking_id);
  return query("SELECT * FROM matches WHERE booking_id = '$booking_id';");
}
function count_all_matches_by_booking($booking_id) {
  $booking_id = db_escape($booking_id);
  return mysqli_fetch_assoc(query("SELECT COUNT(*) AS count FROM matches WHERE booking_id = '$booking_id';"))["count"];
}
function find_all_matches_by_booking_driver($booking_id, $driver_id) {
  $booking_id = db_escape($booking_id); $driver_id = db_escape($driver_id);
  return query("SELECT * FROM matches WHERE booking_id = '$booking_id' AND driver_id = '$driver_id';");
}
function count_all_matches_by_booking_driver($booking_id, $driver_id) {
  $booking_id = db_escape($booking_id); $driver_id = db_escape($driver_id);
  return mysqli_fetch_assoc(query("SELECT COUNT(*) AS count FROM matches WHERE booking_id = '$booking_id' AND driver_id = '$driver_id';"))["count"];
}
function delete_match_by_id($match_id) {
  $match_id = db_escape($match_id); 
  return query("DELETE FROM matches WHERE match_id = '$match_id';");
}
function update_match($match_id, $accept) {
  $match = mysqli_fetch_assoc(find_match_by_id($match_id));
  if ($match) {
    if ($accept) {
      set_booking_driver_by_id($match["booking_id"], $match["driver_id"]);
      // Delete all matches for that booking
      $res = find_all_matches_by_booking($match["booking_id"]);
      while ($temp = mysqli_fetch_assoc($res)) {
        $id = $temp["match_id"];
        delete_match_by_id($id);
      }
      return true;
    } else {
      return delete_match_by_id($match_id);
    }
  }
  return false;
}
