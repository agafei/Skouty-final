<?php
require_once("../../../private/initialize.php");
if (is_post_request()) {
  $preferred_cities = "";
  foreach ($_POST["preferred_cities"] as $city) {
    $preferred_cities .= $city . ",";
  }
  $preferred_cities = rtrim($preferred_cities, ",");
  unset($_POST["preferred_cities"]);

  $_POST = array_map("stripslashes", $_POST);
  extract($_POST);

  $errors = array("password"=>[], "email"=>[], "name"=>[], "license_number"=>[]);

  if (is_blank($password)) $errors["password"][] = "Password cannot be blank";
  if (is_blank($email)) $errors["email"][] = "Email cannot be blank";
  if (is_blank($name)) $errors["name"][] = "Name cannot be blank";
  if (is_blank($license_number)) $errors["name"][] = "License number cannot be blank";

  if (strlen($email) > 255) $errors["email"][] = "Email is too long";
  if (strlen($password) < 8) $errors["password"][] = "Password is too short";

  if (!empty(mysqli_fetch_assoc(find_driver_by_email($email)))) $errors["email"][] = "Email was already used";
  if ($password != $confirm_password) $errors["password"][] = "Passwords do not match";
  if (!preg_match("/[a-z]/", $password)) $errors["password"][] = "Password should contain atleast one lowercase character";
  if (!preg_match("/[A-Z]/", $password)) $errors["password"][] = "Password should contain atleast one uppercase character";
  if (!preg_match("/[0-9]/", $password)) $errors["password"][] = "Password should contain atleast one numerical character";
  if (!preg_match("/[^a-zA-Z\d]/", $password)) $errors["password"][] = "Password should contain atleast one symbol";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"][] = "Invalid email";

  $js_alerts = '';
  foreach ($errors as $field => $msgs) {
      foreach ($msgs as $msg) {
          $js_alerts .= "alert('" . addslashes($msg) . "');\n";
      }
  }

  if (empty($errors["email"]) && empty($errors["password"]) && empty($errors["name"]) && empty($errors["license_number"])) {
    $user = [];
    $user["email"] = $email;
    $user["name"] = $name;
    $user["password"] = $password;
    $user["license_number"] = $license_number;
    $user["preferred_cities"] = $preferred_cities;
    $user["driving_experience"] = $driving_experience;
    $user["base_rate"] = $base_rate;
    $user["overtime_rate"] = $overtime_rate;

    $res = add_driver($user);
    if ($res) {
      print_r($user);
      $user["user_type"] = DRIVER_TYPE;
      $user["user_id"] = mysqli_fetch_assoc(find_driver_by_email($email))["driver_id"];
      log_in_user($user);
      redirect("/driver");
    }
  }
}
$page_title = "Sign up";
$jumbotron_title = "Driver Sign up";
$jumbotron_subtitle = "";
include_once(INCLUDES_PATH . "/header.php");
include_once(INCLUDES_PATH . "/jumbotron.php");
?>

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="path/to/bootstrap.min.css">
<!-- Include Bootstrap Multiselect CSS -->
<link rel="stylesheet" href="path/to/bootstrap-multiselect.css">

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="main bg-white mb-4 p-4">
                <div class="card-body p-3">
                <form method="post">
                    <?php
                    if (!empty($errors)) {

                    }
                    ?>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="name" class="col-form-label">Name</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control" name="name" type="text" placeholder="Name">
                      </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="email" class="col-form-label">Email</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control" name="email" type="email" placeholder="Email">
                      </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="password" class="col-form-label">Password</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control"  name="password" type="password" placeholder="Password">
                      </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="confirm_password" class="col-form-label">Confirm password</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control"  name="confirm_password" type="password" placeholder="Confirm password">
                      </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="license_number" class="col-form-label">License number</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control"  name="license_number" type="text" placeholder="License number">
                      </div>
                    </div>
                    <div class="row g-3 mb-3">
                      <div class="col-md-2">
                        <label for="preferred_cities[]" class="col-form-label">Preferred cities</label>
                      </div>
                      <div class="col-md-10">
                        <select name="preferred_cities[]" id="city-select" multiple class="form-control">
                          <option value="Caloocan City">Caloocan</option>
                          <option value="Calumpang\">Calumpang</option>
                          <option value="Las Pinas">Las Pinas</option>
                          <option value="Makati City">Makati</option>
                          <option value="Malabon">Malabon</option>
                          <option value="Mandaluyong City">Mandaluyong</option>
                          <option value="Manila">Manila</option>
                          <option value="Navotas">Navotas</option>
                          <option value="Niugan">Niugan</option>
                          <option value="Pasay">Pasay</option>
                          <option value="Pasig City">Pasig</option>
                          <option value="Pateros">Pateros</option>
                          <option value="Quezon City">Quezon City</option>
                          <option value="San Juan">San Juan</option>
                          <option value="Taguig">Taguig</option>
                          <option value="Tanza">Tanza</option>
                          <option value="Valenzuela">Valenzuela</option>
                        </select>
                      </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="driving_experience" class="col-form-label">Driving experience</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control"  name="driving_experience" type="number" placeholder="Years">
                      </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="base_rate" class="col-form-label">Base rate (PHP per hour)</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control"  name="base_rate" type="number" placeholder="Base rate">
                      </div>
                    </div>
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-2">
                        <label for="overtime_rate" class="col-form-label">Overtime rate (PHP per hour)</label>
                      </div>
                      <div class="col-md-10">
                        <input class="form-control"  name="overtime_rate" type="number" placeholder="Overtime rate">
                      </div>
                    </div>
                    <div class="row g-3 mt-5 align-items-center">
                      <div class="col mx-auto">
                        <button class="btn bg-yellow black rounded-pill py-2 px-5" name="submit">Sign up</button>
                        <a class="mx-4" href="/driver/login">Already have an account? Login</a>
                      </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="path/to/jquery.min.js"></script>
<!-- Include Bootstrap JS -->
<script src="path/to/bootstrap.bundle.min.js"></script>
<!-- Include Bootstrap Multiselect JS -->
<script src="path/to/bootstrap-multiselect.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#city-select').multiselect({
      includeSelectAllOption: true,
      maxHeight: 200
    });
    // Display alerts if there are any errors
    <?php if(isset($js_alerts)): ?>
        alert("<?php echo $js_alerts; ?>");
    <?php endif; ?>
});
</script>
