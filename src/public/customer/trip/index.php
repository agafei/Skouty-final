<?php
require_once("../../../private/initialize.php");

$page_title = "Customer";
$jumbotron_title = "Trip details";

include_once(INCLUDES_PATH . "/header.php");
include_once(INCLUDES_PATH . "/jumbotron.php");
require_login(CUSTOMER_TYPE);

$booking_id = $_GET["id"];
$booking;
$customer_id = $_SESSION["user_id"];

if ($booking = mysqli_fetch_assoc(find_booking_by_id($booking_id))) {
    if ($booking["customer_id"] != $customer_id) {
        redirect("/customer");
    }
}

$status = 0;
if (isset($booking["driver_id"])) {
    $status = 1;
} else if (count_all_matches_by_booking($booking_id)) {
    $status = 2;
}


if (is_post_request()) {
    $_POST = array_map("stripslashes", $_POST);
    extract($_POST);

    $match = [];
    $match["match_id"] = $match_id;
    $match["accept"] = $accept;

    $res = update_match($match_id, $accept);
    if ($res) {
        
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="main bg-white mb-4 p-4">
                <div class="card my-3">
                    <div class="card-body p-3">
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
                            <li class="list-group-item"><i class="fa fa-map-o" aria-hidden="true"></i> Travel: <span class="py-1 px-3 bg-<?php echo $type_color;?>-subtle rounded-pill"><?php echo $type;?></span></li>
                            <li class="list-group-item"><i class="fa fa-question-circle" aria-hidden="true"></i> Status:  
                            <?php
                            if ($status == 1) {
                                echo "<span class=\"py-1 px-3 bg-success-subtle rounded-pill\">Booked</span>";
                            } else if ($status == 2) {
                                echo "<span class=\"py-1 px-3 bg-warning-subtle rounded-pill\">Matched</span>";
                            } else {
                                echo "<span class=\"py-1 px-3 bg-secondary-subtle rounded-pill\">No matches yet</span>";
                            }
                            ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php
                if ($status == 1) {
                    $driver = mysqli_fetch_assoc(find_driver_by_id($booking["driver_id"]));
                    echo "<div class=\"card my-3\">";
                    echo "<div class=\"card-body p-3\">";
                    echo "<h3 class=\"card-title mb-3\">Driver</h3>";
                    echo "<h5>$driver[name]</h5>";
                    echo "<ul class=\"list-group list-group-flush\">";
                    echo "<li class=\"list-group-item\">License number: $driver[license_number]</li>";
                    echo "<li class=\"list-group-item\">Vehicle information: $driver[vehicle]</li>";
                    echo "<li class=\"list-group-item\">Base rate: ₱$driver[base_rate]/h</li>";
                    echo "<li class=\"list-group-item\">Overtime: ₱$driver[overtime_rate]/h</li>";
                    echo "<li class=\"list-group-item\">Driving experience: $driver[driving_experience] years</li>";
                    echo "<li class=\"list-group-item\"><a class=\"btn bg-yellow rounded-pill px-5 py-2 black\">Contact</a></li>";
                    echo "</ul></div></div>";
                } else {
                    echo "<div class=\"card my-3\">";
                    echo "<div class=\"card-body p-3\">";
                    echo "<h3 class=\"card-title\">Post activity</h3>";
                    echo "<ul class=\"list-group list-group-flush\">";
                    echo "<li class=\"list-group-item\"><i class=\"fa fa-hashtag\" aria-hidden=\"true\"></i> Applicants: " . count_all_matches_by_booking($booking_id) . "</li>";
                    echo "</ul></div></div>";

                    if ($status == 2) {
                        echo "<div class=\"card my-3\">";
                        echo "<div class=\"card-body p-3\">";
                        echo "<h3 class=\"card-title mb-3\">Matches</h3>";
                        $res = find_all_matches_by_booking($booking_id);
                        while ($match = mysqli_fetch_assoc($res)) {
                            $driver = mysqli_fetch_assoc(find_driver_by_id($match["driver_id"]));
                            echo "<div class=\"card my-2\"><div class=\"card-body p-3\">";
                            echo "<h5 class=\"card-title\">$driver[name]</h5>";
                            echo "<ul class=\"list-group list-group-flush\">";
                            echo "<li class=\"list-group-item\">Vehicle information: $driver[vehicle]</li>";
                            echo "<li class=\"list-group-item\">Base rate: ₱$driver[base_rate]/h</li>";
                            echo "<li class=\"list-group-item\">Overtime: ₱$driver[overtime_rate]/h</li>";
                            echo "<li class=\"list-group-item\">Driving experience: $driver[driving_experience] years</li>";
                            echo "<li class=\"list-group-item\">";
                            echo "<form method=\"post\"><input name=\"match_id\" value=\"$match[match_id]\" hidden>";
                            echo "<button class=\"btn bg-yellow px-5 py-2 black rounded-pill mr-2\" name=\"accept\" value=\"1\" type=\"submit\">Request Service</button>";
                            echo "<button class=\"btn bg-secondary-subtle px-5 py-2 black rounded-pill\" name=\"accept\" value=\"0\" type=\"submit\">Decline</button>";
                            echo "</form></li></ul></div></div>";
                        }
                        echo "</div></div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

