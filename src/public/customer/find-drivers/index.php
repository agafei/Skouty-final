<?php
// public/find-drivers/index.php
require_once("../../../../private/initialize.php");

$customer_id = $_SESSION["user_id"];
$page_title = "Find Drivers";
include_once(INCLUDES_PATH . "/header.php");
require_login(CUSTOMER_TYPE);

// Assuming you have a function to find drivers based on the city
$drivers = find_drivers_by_city($_SESSION['search_city'] ?? '');

include_once(INCLUDES_PATH . '/header.php');
?>

<div class="driver-listings">
    <?php foreach ($drivers as $driver): ?>
        <!-- Display each driver's profile -->
        <div class="driver-profile">
            <!-- Use data from $driver to display profile information -->
            <button onclick="requestService(<?php echo h($driver['id']); ?>)">Request Service</button>
        </div>
    <?php endforeach; ?>
</div>

<script>
function requestService(driverId) {
    // Implement the function to request service from a driver
}
</script>
