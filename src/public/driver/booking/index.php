<?php
require_once("../../../private/initialize.php");

$driver_id = $_SESSION["user_id"];
$booking_id = $_GET["id"];
$booking;
if ($res = find_booking_by_id($booking_id)) {
    $booking = mysqli_fetch_assoc($res);
}
$page_title = "Driver";
$jumbotron_title = "Post details";
$jumbotron_subtitle = "";
$status = 0;
if ($booking["driver_id"] == $driver_id) {
    $status = 1;
} else if (count_all_matches_by_booking_driver($booking_id, $driver_id) > 0) {
    $status = 2;
}
include_once(INCLUDES_PATH . "/header.php");
include_once(INCLUDES_PATH . "/jumbotron.php");
require_login(DRIVER_TYPE);

if (is_post_request()) {
    $_POST = array_map("stripslashes", $_POST);
    extract($_POST);

    if (count_all_matches_by_booking_driver($booking_id, $driver_id) == 0) {
        $res = add_match($driver_id, $booking_id);
        if ($res) {
    
        }
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="main bg-white mb-4 p-4">
                <div class="card my-3">
                    <div class="card-body p-3">
                        <form method="post">
                            <?php
                            if ($status == 1) {
                                echo "<button class=\"btn bg-yellow black rounded-pill px-5 py-2 float-right\" disabled>Already booked</button>";
                            } else if ($status == 2) {
                                echo "<button class=\"btn bg-yellow black rounded-pill px-5 py-2 float-right\" disabled>Already applied</button>";
                            } else {
                                echo "<button class=\"btn bg-yellow black rounded-pill px-5 py-2 float-right\" type=\"submit\">Apply & Send Driver Profile</button>";
                            }
                            ?>
                        </form>   
                        <h3 class="card-title"><?php echo $booking["title"]; ?></h3>
                        <p class="card-text"><?php echo $booking["description"]; ?></p>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i> Pickup: <?php echo $booking["pickup"]; ?></li>
                            <li class="list-group-item"><i class="fa fa-map-marker" aria-hidden="true"></i> Destination: <?php echo $booking["destination"]; ?></li>
                            <li class="list-group-item"><i class="fa fa-calendar" aria-hidden="true"></i> Date: <?php echo date_format(date_create($booking["start_time"]), "F d, Y g A") . "-" . date_format(date_create($booking["end_time"]), "F d, Y g A"); ?></li>
                            <?php
                            $type = "One-way";
                            $type_color = "primary";
                            if ($booking["type"] == 1) {
                                $type = "Roundtrip";
                                $type_color = "danger";
                            }
                            ?>
                            <li class="list-group-item"><i class="fa fa-map-o" aria-hidden="true"></i> Travel: <span class="py-1 px-3 bg-<?php echo $type_color; ?>-subtle rounded-pill"><?php echo $type; ?></span></li>
                            <li class="list-group-item"><i class="fa fa-question-circle" aria-hidden="true"></i> Status:  
                            <?php
                            if ($status == 1) {
                                echo "<span class=\"py-1 px-3 bg-success-subtle rounded-pill\">Booked</span>";
                            } else if ($status == 2) {
                                echo "<span class=\"py-1 px-3 bg-warning-subtle rounded-pill\">Applied</span>";
                            } else {
                                echo "<span class=\"py-1 px-3 bg-secondary-subtle rounded-pill\">Not yet applied</span>";
                            }
                            ?></li>
                        </ul>
                    </div>
                </div>
                <?php
                if ($status != 1) {
                    echo "<div class=\"card my-3\">";
                    echo "<div class=\"card-body p-3\">";
                    echo "<h3 class=\"card-title\">Post activity</h3>";
                    echo "<ul class=\"list-group list-group-flush\">";
                    echo "<li class=\"list-group-item\"><i class=\"fa fa-hashtag\" aria-hidden=\"true\"></i> Applicants: " . count_all_matches_by_booking($booking_id) . "</li>";
                    echo "</ul></div></div>";
                }
                ?>
                <div class="card my-3">
                    <div class="card-body p-3">
                        <h3 class="card-title">Customer</h3>
                        <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?php echo mysqli_fetch_assoc(query("SELECT name FROM customers WHERE customer_id = '$booking[customer_id]';"))["name"]; ?></li>
                            <li class="list-group-item"><i class="fa fa-shield" aria-hidden="true"></i> Verified customer</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
