<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    function getRestaurantPrice($restaurantId, $conn) {
        $sql = "SELECT price FROM restaurant WHERE rest_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $restaurantId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['price'];
    }

    // Get the form data
    $customerId = $_SESSION['user_id']; // Assuming user ID is stored in session
    $bookingDate = $_POST['booking_date'];
    $bookingTime = $_POST['booking_time'];
    $numGuests = $_POST['num_guests'];
    $restaurantId = $_POST['restaurant_id'];

    // Get the price of the restaurant
    $restaurantPrice = getRestaurantPrice($restaurantId, $conn);

    // Calculate the total cost
    $totalCost = $numGuests * $restaurantPrice;

    // Prepare and bind the INSERT statement
    $stmt = $conn->prepare("INSERT INTO restaurant_bookings (user_id, booking_date, booking_time, num_guests, total_cost, rest_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdi", $customerId, $bookingDate, $bookingTime, $numGuests, $totalCost, $restaurantId);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method";
}
?>