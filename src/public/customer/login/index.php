<?php
require_once("../../../private/initialize.php");
if (is_post_request()) {
  $_POST = array_map("stripslashes", $_POST);
  extract($_POST);

  $errors = [];
  if (is_blank($password)) $errors[] = "Email cannot be blank";
  if (is_blank($password)) $errors[] = "Password cannot be blank";

  if (empty($errors)) {
    $user = mysqli_fetch_assoc(find_customer_by_email($email));
    
    if ($user && password_verify($password, $user["password"])) {
      $user["user_type"] = CUSTOMER_TYPE;
      $user["user_id"] = $user["customer_id"];
      log_in_user($user);
      redirect("/customer");
      exit;
    } else {$errors[] = "Login is unsuccessful. ";}
  }
}
if (is_logged_in() && $_SESSION["user_type"] == CUSTOMER_TYPE) {
  redirect("/customer");
}
$page_title = "Login";
$jumbotron_title = "Customer Login";
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
                    <button class="btn bg-yellow black rounded-pill py-2 px-5" name="submit">Log in</button>
                    <a class="mx-4" href="/customer/signup">New here? Create an account</a>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
