<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Booking</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="restBook.css">
</head>
<body>
    <header>
        <a href="#" aria-label="Home">
            <img class="logo" src="logo.png" alt="Restaurant Logo">
        </a>
        <nav>
            <ul class="nav-links">
                <li><a href="#" aria-current="page">Home</a></li>
                <li><a href="#">Menu</a></li>
                <li><a href="#">Reservations</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
        <div class="cta-buttons">
            <a href="#" class="cta"><button id="loginBtn">Login</button></a>
        </div>
    </header>
    <main>
        <section class="card-container">
            <div class="card-row">
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

                    // Fetch restaurant data from the database
                    $sql = "SELECT * FROM restaurant";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '
                            <article class="card" data-rest-id="' . $row["rest_id"] . '">
                                <span class="card-number" aria-label="Card Number">01</span>
                                <div class="card-content">
                                    <h2 class="card-title">' . $row["rest_name"] . '</h2>
                                    <p class="card-description">'.$row["rest_desc"].'</p>
                                </div>
                                <figure class="card-image-wrapper">
                                    <img src="rest.jpg" alt="Private Dining Room" class="card-image">
                                </figure>
                                <a href="#" class="card-cta" aria-label="Learn more">
                                    Reserve Now
                                    <span class="card-cta-icon">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </span>
                                </a>
                            </article>
                            ';
                        }
                    } else {
                        echo "No restaurants found.";
                    }

                    $conn->close();
                ?>
            </div>
        </section>
    </main>
    <div class="modal" id="reservationModal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Make a Reservation</h2>
            <form id="reservationForm">
                <input type="hidden" id="restId" name="restId" value="">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="time">Time:</label>
                <input type="time" id="time" name="time" required>

                <label for="guests">Number of Guests:</label>
                <input type="number" id="guests" name="guests" min="1" required>

                <div id="costDisplay"></div>

                <button type="submit">Reserve</button>
            </form>
        </div>
    </div>

    <!-- Login Popup -->
    <div id="loginPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <form id="loginForm" method="post" action="">
                <div class="segment">
                    <h1>Sign In</h1>
                </div>
                <label>
                    <input type="email" placeholder="Email Address" id="emailInput" name="emailInput" required>
                </label>
                <label>
                    <input type="password" placeholder="Password" id="passwordInput" name="passwordInput" required>
                </label>
                <button class="red buttonlog" type="submit">
                    <i class="icon ion-md-lock"></i> Login
                </button>
                <div class="segment">
                    <p>Don't have an account? <a href="#" id="registerBtn">Register</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Registration Popup -->
    <div id="registrationPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <form id="registrationForm" method="post" action="">
                <div class="segment">
                    <h1>Register</h1>
                </div>
                <label>
                    <input type="text" placeholder="Full Name" id="nameInput" name="nameInput" required>
                </label>
                <label>
                    <input type="email" placeholder="Email Address" id="emailRegInput" name="emailRegInput" required>
                </label>
                <label>
                    <input type="password" placeholder="Password" id="passwordRegInput" name="passwordRegInput" required>
                </label>
                <button class="red buttonlog" type="submit">
                    <i class="icon ion-md-lock"></i> Register
                </button>
                <div class="segment">
                    <p>Already have an account? <a href="#" id="loginPopupBtn">Sign In</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Get references to the login button, registration button, and popup elements
        var loginBtn = document.getElementById('loginBtn');
        var loginPopup = document.getElementById('loginPopup');
        var registrationPopup = document.getElementById('registrationPopup');
        var closeBtn = document.getElementsByClassName('close-btn');
        var registerBtn = document.getElementById('registerBtn');
        var loginPopupBtn = document.getElementById('loginPopupBtn');

        // Function to open the login popup
        function openLoginPopup() {
            loginPopup.style.display = 'block';
            registrationPopup.style.display = 'none';
        }

        // Function to open the registration popup
        function openRegistrationPopup() {
            registrationPopup.style.display = 'block';
            loginPopup.style.display = 'none';
        }

        // Function to close the login/registration popup
        function closePopup() {
            loginPopup.style.display = 'none';
            registrationPopup.style.display = 'none';
        }

        // Add event listeners
        loginBtn.addEventListener('click', openLoginPopup);
        registerBtn.addEventListener('click', openRegistrationPopup);
        for (var i = 0; i < closeBtn.length; i++) {
            closeBtn[i].addEventListener('click', closePopup);
        }
        loginPopupBtn.addEventListener('click', openLoginPopup);

        // Close the popup when clicking outside of it
        window.addEventListener('click', function (event) {
            if (event.target == loginPopup || event.target == registrationPopup) {
                closePopup();
            }
        });

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting normally
            
            var emailInput = document.getElementById('emailInput');
            var passwordInput = document.getElementById('passwordInput');

            // Validate input
            if (emailInput.value && passwordInput.value) {
                // Send a POST request to the login.php file
                fetch('login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email: emailInput.value,
                        password: passwordInput.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to user page
                        window.location.href = 'user.html';
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again later.');
                });
            } else {
                alert('Please fill in all the required fields.');
            }
        });

        document.getElementById('registrationForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submitting normally
            
            var nameInput = document.getElementById('nameInput');
            var emailRegInput = document.getElementById('emailRegInput');
            var passwordRegInput = document.getElementById('passwordRegInput');

            // Validate input
            if (nameInput.value && emailRegInput.value && passwordRegInput.value) {
                // Send a POST request to the register.php file
                fetch('register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        name: nameInput.value,
                        email: emailRegInput.value,
                        password: passwordRegInput.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('User registered successfully!');
                        closePopup();
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again later.');
                });
            } else {
                alert('Please fill in all the required fields.');
            }
        });

        // Get the modal and its elements
        const modal = document.getElementById('reservationModal');
        const closeButton = document.getElementsByClassName('close-button')[0];
        const reserveButtons = document.querySelectorAll('.card-cta');
        const costDisplay = document.getElementById('costDisplay');

        // Function to calculate and display the cost
        function displayCost() {
            const guests = document.getElementById('guests').value;
            const cost = guests * 50; // Assuming a cost of $50 per person
            costDisplay.textContent = `Total Cost: $${cost}`;
        }

        // Function to open the modal
        function openModal(restId) {
            document.getElementById('restId').value = restId;
            modal.style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            modal.style.display = 'none';
        }

        // Function to handle reservation submission
        function reserveSeats(event) {
            event.preventDefault(); // Prevent form submission

            // Get the form data
            const form = document.getElementById('reservationForm');
            const formData = new FormData(form);

            // Check if the user is logged in
            fetch('check_login.php')
            .then(response => response.json())
            .then(data => {
                if (data.loggedIn) {
                    // Send the reservation data to the server
                    fetch('reserve.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeModal();
                            alert("Seats reserved successfully");
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again later.');
                    });
                } else {
                    // User is not logged in, show the login popup
                    openLoginPopup();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        }

        // Add event listeners
        closeButton.addEventListener('click', closeModal);
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        // Add event listeners to the "Reserve Now" buttons
        reserveButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                openModal(this.closest('.card').dataset.restId);
            });
        });

        // Add event listener to update the cost on input change
        document.getElementById('guests').addEventListener('input', displayCost);

        // Add event listener to the submit button inside the modal
        document.querySelector('.modal button[type="submit"]').addEventListener('click', reserveSeats);
    </script>

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

        // Login function
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emailInput']) && isset($_POST['passwordInput'])) {
            $email = $_POST['emailInput'];
            $password = $_POST['passwordInput'];

            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    // Login successful
                    session_start();
                    $_SESSION['user_id'] = $row['user_id'];
                    echo json_encode(array('success' => true));
                    exit;
                } else {
                    // Incorrect password
                    echo json_encode(array('success' => false, 'error' => 'Incorrect email or password.'));
                    exit;
                }
            } else {
                // User not found
                echo json_encode(array('success' => false, 'error' => 'Incorrect email or password.'));
                exit;
            }
        }

        // Registration function
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nameInput']) && isset($_POST['emailRegInput']) && isset($_POST['passwordRegInput'])) {
            $name = $_POST['nameInput'];
            $email = $_POST['emailRegInput'];
            $password = password_hash($_POST['passwordRegInput'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $password);

            if ($stmt->execute()) {
                // Registration successful
                echo json_encode(array('success' => true));
                exit;
            } else {
                // Registration failed
                echo json_encode(array('success' => false, 'error' => 'Error registering user. Please try again later.'));
                exit;
            }
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