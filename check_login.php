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

    // Check login function
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['check_login'])) {
        session_start();
        if (isset($_SESSION['user_id'])) {
            echo json_encode(array('loggedIn' => true));
        } else {
            echo json_encode(array('loggedIn' => false));
        }
        exit;
    }

    $conn->close();
?>