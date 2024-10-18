<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tourism";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Reservation function
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['guests']) && isset($_POST['restId'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['guests'];
    $restId = $_POST['restId'];

    // Check if the user is logged in
    session_start();
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Calculate the total cost
        $cost = $guests * 50; // Assuming a cost of $50 per person

        // Insert the reservation data into the database
        $sql = "INSERT INTO restaurant_bookings (user_id, booking_date, booking_time, num_guests, total_cost, rest_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdii", $userId, $date, $time, $guests, $cost, $restId);

        if ($stmt->execute()) {
            // Reservation successful
            echo json_encode(array('success' => true));
            exit;
        } else {
            // Reservation failed
            echo json_encode(array('success' => false, 'error' => 'Error making a reservation. Please try again later.'));
            exit;
        }
    } else {
        // User not logged in
        echo json_encode(array('success' => false, 'error' => 'You need to be logged in to make a reservation.'));
        exit;
    }
}

$conn->close();
?>