<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

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

// Function to fetch all bookings of the logged-in user
function getUserBookings($userId) {
    global $conn;
    $sql = "SELECT rb.*, r.rest_name
            FROM restaurant_bookings rb
            JOIN restaurant r ON rb.rest_id = r.rest_id
            WHERE rb.user_id = $userId";
    $result = $conn->query($sql);

    $bookings = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $booking = array(
                'id' => $row['id'],
                'booking_date' => $row['booking_date'],
                'booking_time' => $row['booking_time'],
                'num_guests' => $row['num_guests'],
                'total_cost' => $row['total_cost'],
                'restaurant' => $row['rest_name']
            );
            $bookings[] = $booking;
        }
    }

    return $bookings;
}

// Function to add a new booking
function addBooking($userId, $bookingDate, $bookingTime, $numGuests, $restaurantId) {
    global $conn;
    $sql = "INSERT INTO restaurant_bookings (user_id, booking_date, booking_time, num_guests, rest_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issii", $userId, $bookingDate, $bookingTime, $numGuests, $restaurantId);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error adding booking: " . $stmt->error;
    }
}

// Function to remove a booking
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

// Get the logged-in user's ID
$userId = $_SESSION['user_id'];

$sql = "SELECT name FROM users WHERE user_id = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["name"];
} 
// Get the user's bookings
$userBookings = getUserBookings($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Bookings</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="userRestBook.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <header>
        <a href="#" aria-label="Home">
            <img class="logo" src="logo.jpg" alt="Page Logo">
        </a>
        <nav>
            <ul class="nav-links">
                <li><a href="login1.php" aria-current="page">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
        </nav>
        <div class="user-info">
            <span>Welcome, <?php echo $username; ?></span>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </header>
    
    <main class="user-bookings">
        <section class="booking-details">
            <h2>Your Bookings</h2>
            <div class="booking-list">
                <?php
                if (!empty($userBookings)) {
                    foreach ($userBookings as $booking) {
                        echo '<div class="booking">';
                        echo '<span><b>Restaurant:</b> ' . $booking['restaurant'] . '</span>';
                        echo '<span><b>Date:</b> ' . $booking['booking_date'] . '</span>';
                        echo '<span><b>Time:</b> ' . $booking['booking_time'] . '</span>';
                        echo '<span><b>Guests:</b> ' . $booking['num_guests'] . '</span>';
                        echo '<span><b>Total Cost:</b> ₹' . $booking['total_cost'] . '</span>';
                        echo '<button class="remove-btn" onclick="removeBooking(' . $booking['id'] . ', ' . $_SESSION['user_id'] . ')"><b>Cancel Booking</b></button>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="booking">No bookings found.</div>';
                }
                ?>
            </div>
            <button class="book-now-btn" onclick="showAddBookingModal()">Book Now</button>
        </section>
    </main>

    <!-- Add booking modal -->
    <div id="addBookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddBookingModal()">&times;</span>
            <h2>Add New Booking</h2>
            <form id="addBookingForm">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <label for="booking-date">Booking Date:</label>
                <input type="date" id="booking-date" name="booking_date" required>
                <label for="booking-time">Booking Time:</label>
                <input type="time" id="booking-time" name="booking_time" required>
                <label for="num-guests">Number of Guests:</label>
                <input type="number" id="num-guests" name="num_guests" required>
                <label for="restaurant-id">Restaurant:</label>
                <select id="restaurant-id" name="restaurant_id" required>
                    <?php
                    // Fetch restaurant options from database
                    $sql = "SELECT rest_id, rest_name FROM restaurant";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['rest_id'] . '">' . $row['rest_name'] . '</option>';
                        }
                    }
                    ?>
                </select>
                <button type="button" class="submit-btn" onclick="submitForm()">Add Booking</button>
            </form>
        </div>
    </div>

    <script>
        function showAddBookingModal() {
            document.getElementById('addBookingModal').style.display = 'block';
        }

        function closeAddBookingModal() {
            document.getElementById('addBookingModal').style.display = 'none';
        }

        function submitForm() {
            var form = document.getElementById("addBookingForm");
            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "addBookingUser.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    if (xhr.responseText === "success") {
                        closeAddBookingModal();
                        window.location.reload();
                    } else {
                        alert(xhr.responseText);
                    }
                } else {
                    console.error(xhr.responseText);
                }
            };
            xhr.send(formData);
        }

        function removeBooking(bookingId, userId) {
            if (confirm("Are you sure you want to remove this booking?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "rem_book_user.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        window.location.reload();
                    } else {
                        console.error(xhr.responseText);
                    }
                };
                xhr.send("booking_id=" + bookingId + "&user_id=" + userId);
            }
        }
    </script>
</body>
</html>