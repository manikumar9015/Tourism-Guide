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

// Function to remove a booking from the database
function removeBookingFromDB($bookingId) {
    global $conn;
    $sql = "DELETE FROM restaurant_bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    
    try {
        if ($stmt->execute()) {
            echo "Booking removed successfully.";
        } else {
            echo "Error removing booking: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Check if the bookingId parameter is received
if (isset($_POST['bookingId'])) {
    // Get the bookingId from the POST data
    $bookingId = $_POST['bookingId'];

    // Echo the received bookingId for debugging
    echo "Received bookingId: " . $bookingId;

    // Call the removeBookingFromDB function
    removeBookingFromDB($bookingId);

    // Close the database connection
    $conn->close();
} else {
    // If bookingId parameter is not provided, return an error response
    http_response_code(400);
    echo "Booking ID not provided.";
}
?>