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

// Function to fetch all users from the database
function getAllUsers() {
    global $conn;
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    $users = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = array(
                'user_id' => $row['user_id'],
                'name' => $row['name'],
                'email' => $row['email']
            );
            $users[] = $user;
        }
    }

    return $users;
}

// Function to fetch only users from the database
function getOnlyUsers() {
    global $conn;
    $sql = "SELECT * FROM users WHERE role='user'";
    $result = $conn->query($sql);

    $users = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = array(
                'user_id' => $row['user_id'],
                'name' => $row['name'],
                'email' => $row['email']
            );
            $users[] = $user;
        }
    }

    return $users;
}

// Function to fetch all restaurants from the database
function getAllRestaurants() {
    global $conn;
    $sql = "SELECT * FROM restaurant";
    $result = $conn->query($sql);

    $restaurants = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $restaurant = array(
                'rest_id' => $row['rest_id'],
                'rest_name' => $row['rest_name'],
                'city' => $row['city']
            );
            $restaurants[] = $restaurant;
        }
    }

    return $restaurants;
}

// Function to remove a user from the database
function removeUserFromDB($userId) {
    global $conn;
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// Function to remove a booking from the database
function removeBookingFromDB($bookingId) {
    global $conn;
    $sql = "DELETE FROM restaurant_bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);
    $stmt->execute();
}

// Function to add a booking to the database
function addBookingToDB($customerId, $bookingDate, $bookingTime, $numGuests, $totalCost, $restaurantId) {
    global $conn;
    $sql = "INSERT INTO restaurant_bookings (user_id, booking_date, booking_time, num_guests, total_cost, rest_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdi", $customerId, $bookingDate, $bookingTime, $numGuests, $totalCost, $restaurantId);
    $stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <header>
        <a href="#" aria-label="Home">
            <img class="logo" src="logo.jpg" alt="Page Logo">
        </a>
        <nav>
            <ul class="nav-links">
                <!-- <li><a href="login1.php" aria-current="page">Home</a></li> -->
                <!-- <li><a href="#">About</a></li> -->
                <!-- <li><a href="#">Contact</a></li> -->
            </ul>
        </nav>
        <div class="cta-buttons">
            <a href="logout.php" class="cta"><button>Logout</button></a>
        </div>
    </header>

    <main class="admin-dashboard">
        <section class="user-details">
            <h2>User Details</h2>
            <div class="user-list">
                <?php
                $users = getAllUsers();
                foreach ($users as $user) {
                    echo '<div class="user">';
                    echo '<span><b>Name:</b> ' . $user['name'] . '</span>';
                    echo '<span><b>Email:</b> ' . $user['email'] . '</span>';
                    echo '<button class="remove-btn" onclick="removeUser(' . $user['user_id'] . ')">Remove</button>';
                    echo '</div>';
                }
                ?>
            </div>
            <button class="add-user-btn" onclick="showAddUserModal()">Add New User</button>
        </section>

        <section class="hotel-bookings">
            <h2>Hotel Bookings</h2>
            <div class="booking-list">
                <?php
                $sql = "SELECT rb.*, u.name, r.rest_name
                        FROM restaurant_bookings rb
                        JOIN users u ON rb.user_id = u.user_id
                        LEFT JOIN restaurant r ON rb.rest_id = r.rest_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="booking">';
                        echo '<span><b>Name:</b><br>' . $row['name'] . '</span>';
                        echo '<span><b>Restaurant Name: </b><br>' . $row['rest_name'] . '</span>';
                        echo '<span><b>Booking Date: </b><br>' . $row['booking_date'] . '</span>';
                        echo '<span><b>Booking Time: </b><br>' . $row['booking_time'] . '</span>';
                        echo '<span><b>Number of Guests: </b><br>' . $row['num_guests'] . '</span>';
                        echo '<span><b>Total Cost: </b><br>₹' . $row['total_cost'] . '</span>';
                        echo '<button class="remove-btn" onclick="removeBooking(' . $row['id'] . ')">Remove</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="booking">No bookings found.</div>';
                }
                ?>
            </div>
            <button class="add-booking-btn" onclick="showAddBookingModal()">Add New Booking</button>
        </section>
    </main>

    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddUserModal()">&times;</span>
            <h2>Add New User</h2>
            <form id="addUserForm">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="button" class="submit-btn" onclick="addUser()">Add User</button>
            </form>
        </div>
    </div>

    <div id="addBookingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddBookingModal()">&times;</span>
            <h2>Add New Booking</h2>
            <form id="addBookingForm">
                <label for="customer">Customer:</label>
                <select id="customer" name="customer" required>
                    <option value="">Select a customer</option>
                    <?php
                    $users = getOnlyUsers();
                    foreach ($users as $user) {
                        echo "<option value='" . $user['user_id'] . "'>" . $user['name'] . "</option>";
                    }
                    ?>
                </select>
                <label for="booking-date">Booking Date:</label>
                <input type="date" id="booking-date" name="booking-date" required>
                <label for="booking-time">Booking Time:</label>
                <input type="time" id="booking-time" name="booking-time" required>
                <label for="num-guests">Number of Guests:</label>
                <input type="number" id="num-guests" name="num-guests" required>
                <!-- <label for="total-cost">Total Cost:</label>
                <input type="number" step="0.01" id="total-cost" name="total-cost" required> -->
                <label for="restaurant">Restaurant:</label>
                <select id="restaurant" name="restaurant" required>
                    <option value="">Select a restaurant</option>
                    <?php
                    $restaurants = getAllRestaurants();
                    foreach ($restaurants as $restaurant) {
                        echo "<option value='" . $restaurant['rest_id'] . "'>" . $restaurant['rest_name'] . "</option>";
                    }
                    ?>
                </select>
                <button type="button" class="submit-btn" onclick="addBooking()">Add Booking</button>
            </form>
        </div>
    </div>

    <script>
        function showAddUserModal() {
            document.getElementById('addUserModal').style.display = 'block';
        }

        function closeAddUserModal() {
            document.getElementById('addUserModal').style.display = 'none';
        }

        function removeUser(userId) {
    if (confirm('Are you sure you want to remove this user?')) {
        // Make an AJAX request to the PHP function
        var xhr = new XMLHttpRequest();
        var url = 'remove_user.php';
        var params = 'userId=' + userId; // Include the userId parameter
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Refresh the user list or show a success message
                location.reload();
            }
        };
        // Send the user ID in the request body
        xhr.send(params);
    }
}


        function addUser() {
            // Get form data
            var name = document.getElementById('name').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var role = document.getElementById('role').value;

            console.log("Attempting to add user...");

            // Make an AJAX request to add a new user
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        console.log("User added successfully.");
                        // Close modal and refresh user list
                        closeAddUserModal();
                        location.reload();
                    } else {
                        console.error("Error adding user:", xhr.statusText);
                    }
                }
            };
            xhr.send('name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password) + '&role=' + encodeURIComponent(role));
        }

        function showAddBookingModal() {
            document.getElementById('addBookingModal').style.display = 'block';
        }

        function closeAddBookingModal() {
            document.getElementById('addBookingModal').style.display = 'none';
        }

        function removeBooking(bookingId) {
            if (confirm('Are you sure you want to remove this booking?')) {
                // Make an AJAX request to remove the booking
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'remove_booking.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Refresh the booking list or show a success message
                        location.reload();
                    }
                };
                xhr.send('bookingId=' + encodeURIComponent(bookingId));
            }
        }

        function addBooking() {
            // Get form data
            var customerId = document.getElementById('customer').value;
            var bookingDate = document.getElementById('booking-date').value;
            var bookingTime = document.getElementById('booking-time').value;
            var numGuests = document.getElementById('num-guests').value;
            // var totalCost = document.getElementById('total-cost').value;
            var restaurantId = document.getElementById('restaurant').value;

            console.log("Attempting to add booking...");

            // Make an AJAX request to add a new booking
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_booking.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        console.log("Booking added successfully.");
                        // Close modal and refresh booking list
                        closeAddBookingModal();
                        location.reload();
                    } else {
                        console.error("Error adding booking:", xhr.statusText);
                    }
                }
            };
            xhr.send('customer=' + encodeURIComponent(customerId) + '&booking_date=' + encodeURIComponent(bookingDate) + '&booking_time=' + encodeURIComponent(bookingTime) + '&num_guests=' + encodeURIComponent(numGuests) + /*'&total_cost=' + encodeURIComponent(totalCost) +*/ '&restaurant=' + encodeURIComponent(restaurantId));
        }
    </script>
</body>
</html>
