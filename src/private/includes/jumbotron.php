<?php
if (!isset($jumbotron_title)) {
  $jumbotron_title = "";
}
if (!isset($jumbotron_subtitle)) {
  $jumbotron_subtitle = "";
}
?>
<div class="container-fluid py-5 bg-yellow" style="margin-bottom: -5rem; border-radius: 0;">
  <div class="container my-5 py-5">
    <div class="row">
      <div class="col-lg-7">
        <h1 class="display-4 d-inline font-weight-bold"><?php echo $jumbotron_title; ?></h1>
        <h3><?php echo $jumbotron_subtitle; ?></h3>
      </div>
    </div>
  </div>
</div>