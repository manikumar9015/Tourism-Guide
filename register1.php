<?php
// Include your database configuration file
require_once 'config.php';

// Check if the connection is successful
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nameInput"])) {
    // Retrieve form data
    $name = $_POST["nameInput"];
    $email = $_POST["emailRegInput"];
    $password = password_hash($_POST["passwordRegInput"], PASSWORD_DEFAULT);
    $role = "user";

    // Your SQL query to insert new user data into the database
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    
    if ($stmt->execute()) {
        $message = "Registration successful!";
        // Display the alert message to the user
        echo '<script>
                    alert("' . $message . '");
              </script>';
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Close the database connection
$conn->close();
?>