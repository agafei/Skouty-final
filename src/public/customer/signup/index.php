<?php
require_once("../../../private/initialize.php");
if (is_post_request()) {
  $_POST = array_map("stripslashes", $_POST);
  extract($_POST);

  $errors = array("password"=>[], "email"=>[], "name"=>[]);

  if (is_blank($password)) $errors["password"][] = "Password cannot be blank";
  if (is_blank($email)) $errors["email"][] = "Email cannot be blank";
  if (is_blank($name)) $errors["name"][] = "Name cannot be blank";

  if (strlen($email) > 255) $errors["email"][] = "Email is too long";
  if (strlen($password) < 8) $errors["password"][] = "Password is too short";

  if (!empty(mysqli_fetch_assoc(find_customer_by_email($email)))) $errors["email"][] = "Email was already used";
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

  if (empty($errors["email"]) && empty($errors["password"])) {
    $user = [];
    $user["email"] = $email;
    $user["name"] = $name;
    $user["password"] = $password;

    $res = add_customer($user);
    if ($res) {
      $user["user_type"] = CUSTOMER_TYPE;
      $user["user_id"] = mysqli_fetch_assoc(find_customer_by_email($email))["customer_id"];
      log_in_user($user);
      redirect("/customer");
    
    }
  }
}
$page_title = "Sign up";
$jumbotron_title = "Customer Sign up";
$jumbotron_subtitle = "";
include_once(INCLUDES_PATH . "/header.php");
include_once(INCLUDES_PATH . "/jumbotron.php");
?>
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
                    <button class="btn bg-yellow black rounded-pill py-2 px-5" name="submit">Sign up</button>
                    <a class="mx-4" href="/customer/login">Already have an account? Login</a>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#city-select').multiselect({
          includeSelectAllOption: true,
          maxHeight: 200
        });
        <?php echo $js_alerts; ?>
    });
</script>
