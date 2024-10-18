<?php
require_once 'config.php';

// Login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["emailInput"])) {
    $email = $_POST["emailInput"];
    $password = $_POST["passwordInput"];
    $role = $_POST["roleSelect"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result !== false) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"]) && $row["role"] == $role) {
                session_start();
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["role"] = $row["role"];
                if ($role == "admin") {
                    header("Location: admin.php");
                } else {
                    header("Location: user.php");
                }
                exit;
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Error executing query: " . $conn->error;
    }
}
?>