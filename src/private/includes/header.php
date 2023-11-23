<?php
if (!isset($page_title)) $page_title = "Home";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title><?php echo $page_title; ?> | Skouty</title>
  <link rel=icon href=/assets/images/favicon.png>
  <!-- Font Awesome -->
  <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <!-- JQuery and Popper -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
  <!-- Bootstrap Multiselect Plugin -->
  <link rel="stylesheet" href="/assets/bootstrap-multiselect.min.css" type="text/css">
  <script type="text/javascript" src="/assets/bootstrap-multiselect.min.js"></script>
  <!-- Google Maps API -->
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <!--  -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/styles.css" type="text/css">
</head>
<body>
<navbar class="navbar text-warning bg-black">
    <div class="container-fluid">
        <a class="navbar-brand yellow font-weight-bold" href="/">Skouty</a>
        <div class="d-flex">
            <?php
            if (is_logged_in()) {
              echo "<a class=\"btn btn-outline-danger\" href=\"";
              if ($_SESSION["user_type"] == CUSTOMER_TYPE) { 
                echo "/customer/logout";
              } else { 
                echo "/driver/logout"; 
              }
              echo "\">Log out</a>";
            }
            ?>
        </div>
    </div>
</navbar>
