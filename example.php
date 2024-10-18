<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Destinations</title>
    <link rel="stylesheet" href="home.css">
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>

<body>
    <header>
        <a href="#" aria-label="Home">
            <img class="logo" src="logo.jpg" alt="Page Logo">
        </a>
        <nav>
            <ul class="nav-links">
                <li><a href="#" aria-current="page">Home</a></li>
                <!-- <li><a href="#">Menu</a></li> -->
                <!-- <li><a href="#">Reservations</a></li> -->
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
                <article class="card full-width">
                    <div class="card-content">
                        <h2 class="card-title">Udupi</h2>
                        <p class="card-description">Explore the vibrant coastal town of Udupi, known for its rich
                            cultural heritage, delectable cuisine, and serene beaches.</p>
                    </div>
                    <figure class="card-image-wrapper">
                        <img src="Udupi.jpeg" alt="Udupi City" class="card-image">
                        <div class="card-image-overlay">Udupi</div>
                    </figure>
                    <a href="udupi.html" class="card-cta" aria-label="Learn more about Udupi">
                        Discover Udupi
                        <span class="card-cta-icon">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                </article>
                <article class="card full-width">
                    <div class="card-content">
                        <h2 class="card-title">Mangalore</h2>
                        <p class="card-description">Uncover the captivating charm of Mangalore, a city that seamlessly
                            blends history, culture, and natural beauty.</p>
                    </div>
                    <figure class="card-image-wrapper">
                        <img src="mng.jpeg" alt="Mangalore City" class="card-image">
                        <div class="card-image-overlay">Mangalore</div>
                    </figure>
                    <a href="mangalore.html" class="card-cta" aria-label="Learn more about Mangalore">
                        Explore Mangalore
                        <span class="card-cta-icon">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                </article>
            </div>
        </section>
    </main>

    <!-- Registration Popup -->
    <div id="registrationPopup" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <form id="registrationForm" method="post">
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
        // Get references to the registration form and registration popup
        var registrationForm = document.getElementById('registrationForm');
        var registrationPopup = document.getElementById('registrationPopup');

        // Function to handle form submission
        registrationForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission behavior

            // Get form data
            var formData = new FormData(this);

            // Make an AJAX request to register.php
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'register.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Display alert message
                    alert(xhr.responseText);
                    // Close registration popup
                    registrationPopup.style.display = 'none';
                }
            };
            xhr.send(formData);
        });
    </script>

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
        $password = $_POST["passwordRegInput"];
        $role = "user";

        // Your SQL query to insert new user data into the database
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        $message = "Registration successful!";
        if ($stmt->execute()) {
            echo $message;
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the database connection
    $conn->close();
    ?>
</body>

</html>
