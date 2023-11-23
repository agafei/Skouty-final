<?php
require_once("../../private/initialize.php");

$page_title = "Customer";
$customer_id = $_SESSION["user_id"];
$name = mysqli_fetch_assoc(find_customer_by_id($customer_id))["name"];
$jumbotron_title = "Welcome, " . $name;
$jumbotron_subtitle = "What can we do for you?";

include_once(INCLUDES_PATH . "/header.php");
include_once(INCLUDES_PATH . "/jumbotron.php");
require_login(CUSTOMER_TYPE);
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="main bg-white mb-4 p-4">
                <div class="card-body p-3">
                    <a class="btn bg-yellow black rounded-pill px-5 py-2 float-right" href="/customer/trip/create"><i class="fa fa-plus" aria-hidden="true"></i> Plan a trip</a>
                    <h3 class="mb-4">My Trips</h3>
                    <?php
                    $res = find_all_bookings_by_customer($customer_id);
                    while ($booking = mysqli_fetch_assoc($res)) {
                        echo "<div class=\"card my-3\"><div class=\"card-body\">";
                        echo "<h5 class=\"card-title\">$booking[title]</h5>";
                        echo "<p class=\"card-text\">$booking[description]</p>";
                        echo "<ul class=\"list-group list-group-flush\">";
                        echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Pickup: $booking[pickup]</li>";
                        echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Destination: $booking[destination]</li>";
                        echo "<li class=\"list-group-item\"><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> Date: " . date_format(date_create($booking["start_time"]), "F d, Y g A") . "-" . date_format(date_create($booking["end_time"]), "F d, Y g A") . "</li>";
                        $type = "One-way";
                        $type_color = "primary";
                        if ($booking["type"] == 1) {
                            $type = "Roundtrip";
                            $type_color = "danger";
                        }
                        echo "<li class=\"list-group-item\"><i class=\"fa fa-map-o\" aria-hidden=\"true\"></i> Travel: <span class=\"py-1 px-3 bg-$type_color-subtle rounded-pill\">$type</span></li>";
                        $status = "No matches yet";
                        $status_color = "secondary";
                        if (isset($booking["driver_id"])) {
                            $status = "Booked";
                            $status_color = "success";
                        } else if (count_all_matches_by_booking($booking["booking_id"]) > 0) {
                            $status = "Matched";
                            $status_color = "warning";
                        }
                        echo "<li class=\"list-group-item\"><i class=\"fa fa-question-circle\" aria-hidden=\"true\"></i> Status:  <span class=\"py-1 px-3 bg-$status_color-subtle rounded-pill\">$status</span></li>";
                        echo "<li class=\"list-group-item\">";
                        echo "<a class=\"card-link\" href=\"/customer/trip/?id=$booking[booking_id]\">Read more</a>";
                        echo "</li></ul></div></div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
        

