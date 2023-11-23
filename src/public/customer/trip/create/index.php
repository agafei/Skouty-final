<?php
require_once("../../../../private/initialize.php");

$customer_id = $_SESSION["user_id"];
$page_title = "Customer";
include_once(INCLUDES_PATH . "/header.php");
require_login(CUSTOMER_TYPE);

if (is_post_request()) {
    $_POST = array_map("stripslashes", $_POST);
    extract($_POST);

    $booking = [];
    $booking["customer_id"] = $customer_id;
    $booking["title"] = $title;
    $booking["description"] = $description;
    $booking["type"] = $type;
    $booking["pickup"] = $pickup;
    $booking["destination"] = $destination;
    $booking["start_time"] = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $start_time)));
    $booking["end_time"] = date("Y-m-d H:i:s", strtotime(str_replace("T", " ", $end_time)));

    
    $res = add_booking($booking);
    if ($res) {
        redirect("/customer");
    }
}
?>
<script>
function initializeMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 14.6669568, lng: 121.0384384 },
        zoom: 13,
        mapTypeId: "roadmap",
    });
    const pickupSearch = new google.maps.places.SearchBox(document.getElementById("pickup-input"));
    const destinationSearch = new google.maps.places.SearchBox(document.getElementById("destination-input"));

    map.addListener("bounds_changed", () => {
        pickupSearch.setBounds(map.getBounds());
        destinationSearch.setBounds(map.getBounds());
    });

    let markers = [];
    [pickupSearch, destinationSearch].forEach(searchBox => {
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
            return;
            }
            markers.forEach((marker) => {
            marker.setMap(null);
            });
            markers = [];
            const bounds = new google.maps.LatLngBounds();

            places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }

            const icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25),
            };
            markers.push(
                new google.maps.Marker({
                map,
                icon,
                title: place.name,
                position: place.geometry.location,
                }),
            );
            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
            });
            map.fitBounds(bounds);
        });
    });
}

window.initializeMap = initializeMap;
</script>

<style>
    /* Custom CSS styles for the "Match" button */
    .btn-match {
        width: 67%; /* Make the button 100% width of its container */
        padding: 10px; /* Add padding to increase the button's length */
        background-color: black; /* Set the background color to black */
        color: #FFE65E; /* Set the text color to yellow */
        border: none; /* Remove the button border */
        cursor: not-allowed; /* Change the cursor to not-allowed to indicate it's disabled */
    }
</style>

<div class="container position-relative">
    <div class="row">
        <div class="col-12">
            <div class="main bg-white position-absolute top-0 mt-3" style="right: 0; z-index: 1000">
                <div class="card-body p-3">
                    <form method="post">
                        <div class="mb-2">
                            <input class="form-control" name="title" type="text" placeholder="Title">
                        </div>
                        <div class="mb-2">
                            <textarea class="form-control" name="description" rows="4" placeholder="Description"></textarea>
                        </div>
                        <div class="mb-2">
                            <input class="form-control" id="pickup-input" name="pickup" type="text" placeholder="Pickup">
                        </div>
                        <div class="mb-2">
                            <select class="custom-select" name="type">
                                <option value="0">One-way</option>
                                <option value="1">Roundtrip</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <input class="form-control" name="start_time" type="datetime-local" placeholder="Start time">
                        </div>
                        <div class="mb-2">
                            <input class="form-control" id="destination-input" name="destination" type="text" placeholder="Destination">
                        </div>
                        <div class="mb-3">
                            <input class="form-control" name="end_time" type="datetime-local" placeholder="End time">
                        </div>
                        <div class="text-center">
                            <button class="btn bg-yellow black px-5 py-2 rounded-pill" name="action" value="post_and_hire" type="submit">Post & Hire</button>
                        </div>
                        </div>
                        <div class="text-center mb-3">
                            <button class="px-5 py-2 rounded-pill btn-match" name="action" value="match" type="button" disabled>Match</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>
<div id="map" style="z-index: 0"></div>

<script
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAPS_API_KEY; ?>&callback=initializeMap&libraries=places&v=weekly"
    defer
></script>
