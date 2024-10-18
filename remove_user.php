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

// Function to remove a user from the database
function removeUserFromDB($userId) {
    global $conn;
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    
    try {
        if ($stmt->execute()) {
            echo "User removed successfully.";
        } else {
            echo "Error removing user: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Check if the userId parameter is received
if (isset($_POST['userId'])) {
    // Get the userId from the POST data
    $userId = $_POST['userId'];

    // Echo the received userId for debugging
    echo "Received userId: " . $userId;

    // Call the removeUserFromDB function
    removeUserFromDB($userId);

    // Close the database connection
    $conn->close();
} else {
    // If userId parameter is not provided, return an error response
    http_response_code(400);
    echo "User ID not provided.";
}
?>