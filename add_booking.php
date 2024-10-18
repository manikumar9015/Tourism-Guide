<?php
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tourism";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Function to get the price of a specific restaurant
function getRestaurantPrice($restaurantId) {
    global $conn;
    $sql = "SELECT price FROM restaurant WHERE rest_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $restaurantId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['price'];
}

// Get the form data
$customerId = $_POST['customer'];
$bookingDate = $_POST['booking_date'];
$bookingTime = $_POST['booking_time'];
$numGuests = $_POST['num_guests'];
$restaurantId = $_POST['restaurant'];

// Get the price of the restaurant
$restaurantPrice = getRestaurantPrice($restaurantId);

// Calculate the total cost
$totalCost = $numGuests * $restaurantPrice;// Insert the booking into the database

$sql = "INSERT INTO restaurant_bookings (user_id, booking_date, booking_time, num_guests, total_cost, rest_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssdi", $customerId, $bookingDate, $bookingTime, $numGuests, $totalCost, $restaurantId);

if ($stmt->execute()) {
    echo "Booking added successfully.";
} else {
    echo "Error adding booking: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>