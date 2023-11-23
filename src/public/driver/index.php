<?php
require_once("../../private/initialize.php");

$page_title = "Driver";
$driver_id = $_SESSION["user_id"];
$name = mysqli_fetch_assoc(find_driver_by_id($driver_id))["name"];
$jumbotron_title = "Welcome, " . $name;
$jumbotron_subtitle = "Jobs for you";

include_once(INCLUDES_PATH . "/header.php");
include_once(INCLUDES_PATH . "/jumbotron.php");
require_login(DRIVER_TYPE);
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="main bg-white mb-4 p-4">
                <div class="card-body p-3">
                    <ul class="nav nav-pills mb-4" id="tab-list" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill text-dark px-4" id="feed-tab" data-toggle="pill" href="#feed-tab-pane" role="tab" aria-controls="feed-tab-pane" aria-selected="true">Feed</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill text-dark px-4" id="applied-tab" data-toggle="pill" href="#applied-tab-pane" role="tab" aria-controls="applied-tab-pane" aria-selected="false">Applied</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill text-dark px-4" id="booked-tab" data-toggle="pill" href="#booked-tab-pane" role="tab" aria-controls="booked-tab-pane" aria-selected="false">Booked</button>
                        </li>
                    </ul>
                    <div class="tab-content border-0" id="tab-content">
                        <div class="tab-pane fade show active" id="feed-tab-pane" role="tabpanel" aria-labelledby="feed-tab" tabindex="0">
                            <?php
                            $res = find_all_bookings_by_driver_preference($driver_id);
                            if ($res) {
                                while ($booking = mysqli_fetch_assoc($res)) {
                                    if (isset($booking["driver_id"]) || mysqli_fetch_assoc(find_all_matches_by_booking_driver($booking["booking_id"], $driver_id))) {
                                        continue;
                                    }
                                    echo "<div class=\"card my-3\"><div class=\"card-body\">";
                                    echo "<h4 class=\"card-title\">$booking[title]</h4>";
                                    echo "<p class=\"card-text\">$booking[description]</p>";
                                    echo "<ul class=\"list-group list-group-flush\">";
                                    echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Pickup: $booking[pickup]</li>";
                                    echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Destination: $booking[start_time]</li>";
                                    echo "<li class=\"list-group-item\"><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> Date: " . date_format(date_create($booking["start_time"]), "F d, Y g A") . "-" . date_format(date_create($booking["end_time"]), "F d, Y g A") . "</li>";
                                    $type = "One-way";
                                    $type_color = "primary";
                                    if ($booking["type"] == 1) {
                                        $type = "Roundtrip";
                                        $type_color = "danger";
                                    }
                                    echo "<li class=\"list-group-item\"><i class=\"fa fa-map-o\" aria-hidden=\"true\"></i> Travel: <span class=\"py-1 px-3 bg-$type_color-subtle rounded-pill\">$type</span></li>";
                                    echo "<li class=\"list-group-item\">";
                                    echo "<a class=\"card-link\" href=\"/driver/booking/?id=$booking[booking_id]\">Read more</a>";
                                    echo "</li></ul></div></div>";
                                }
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="applied-tab-pane" role="tabpanel" aria-labelledby="applied-tab" tabindex="0">
                            <?php
                            $res = query("SELECT booking_id FROM matches WHERE driver_id='$driver_id' GROUP BY booking_id;");
                            while ($temp = mysqli_fetch_assoc($res)) {
                                $booking_id = $temp["booking_id"];
                                $booking = mysqli_fetch_assoc(find_booking_by_id($booking_id));
                                echo "<div class=\"card my-3\"><div class=\"card-body\">";
                                echo "<h4 class=\"card-title\">$booking[title]</h4>";
                                echo "<p class=\"card-text\">$booking[description]</p>";
                                echo "<ul class=\"list-group list-group-flush\">";
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Pickup: $booking[pickup]</li>";
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Destination: $booking[start_time]</li>";
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> Date: " . date_format(date_create($booking["start_time"]), "F d, Y g A") . "-" . date_format(date_create($booking["end_time"]), "F d, Y g A") . "</li>";
                                $type = "One-way";
                                $type_color = "primary";
                                if ($booking["type"] == 1) {
                                    $type = "Roundtrip";
                                    $type_color = "danger";
                                }
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-map-o\" aria-hidden=\"true\"></i> Travel: <span class=\"py-1 px-3 bg-$type_color-subtle rounded-pill\">$type</span></li>";
                                echo "<li class=\"list-group-item\">";
                                echo "<a class=\"card-link\" href=\"/driver/booking/?id=$booking[booking_id]\">Read more</a>";
                                echo "</li></ul></div></div>";
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="booked-tab-pane" role="tabpanel" aria-labelledby="booked-tab" tabindex="0">
                            <?php
                            $res = find_all_bookings_by_driver($driver_id);
                            while ($booking = mysqli_fetch_assoc($res)) {
                                echo "<div class=\"card my-3\"><div class=\"card-body\">";
                                echo "<h4 class=\"card-title\">$booking[title]</h4>";
                                echo "<p class=\"card-text\">$booking[description]</p>";
                                echo "<ul class=\"list-group list-group-flush\">";
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Pickup: $booking[pickup]</li>";
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i> Destination: $booking[start_time]</li>";
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> Date: " . date_format(date_create($booking["start_time"]), "F d, Y g A") . "-" . date_format(date_create($booking["end_time"]), "F d, Y g A") . "</li>";
                                $type = "One-way";
                                $type_color = "primary";
                                if ($booking["type"] == 1) {
                                    $type = "Roundtrip";
                                    $type_color = "danger";
                                }
                                echo "<li class=\"list-group-item\"><i class=\"fa fa-map-o\" aria-hidden=\"true\"></i> Travel: <span class=\"py-1 px-3 bg-$type_color-subtle rounded-pill\">$type</span></li>";
                                echo "<li class=\"list-group-item\">";
                                echo "<a class=\"card-link\" href=\"/driver/booking/?id=$booking[booking_id]\">Read more</a>";
                                echo "</li></ul></div></div>";
                            }
                            ?>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
