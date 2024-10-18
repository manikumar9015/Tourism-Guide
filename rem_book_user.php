<?php
session_start();

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

// Get the booking ID and user ID from the request
$bookingId = $_POST['booking_id'];
$userId = $_POST['user_id'];

// Call the removeBooking function
removeBooking($bookingId, $userId);

function removeBooking($bookingId, $userId) {
    global $conn;
    $sql = "DELETE FROM restaurant_bookings WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $bookingId, $userId);
    if ($stmt->execute()) {
        echo "Booking removed successfully.";
    } else {
        echo "Error removing booking: " . $stmt->error;
    }
}