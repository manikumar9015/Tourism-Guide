<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourist Destinations</title>
    <link rel="stylesheet" href="home.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>

<body>
    <header>
        <a href="#" aria-label="Home">
            <img class="logo" src="logo.jpg" alt="Page Logo">
        </a>
        <nav>
            <ul class="nav-links">
                <!-- <li><a href="#" aria-current="page">Home</a></li> -->
                <!-- <li><a href="#">Menu</a></li> -->
                <!-- <li><a href="#">Reservations</a></li> -->
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
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
                    <a href="UdupiPlaces.html" class="card-cta" aria-label="Learn more about Udupi">
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
                    <a href="MangPlaces.html" class="card-cta" aria-label="Learn more about Mangalore">
                        Explore Mangalore
                        <span class="card-cta-icon">
                            <i class="ri-arrow-right-s-line"></i>
                        </span>
                    </a>
                </article>
            </div>
        </section>
    </main>

    <!-- Login Popup -->
    <div id="loginPopup" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <form id="loginForm" method="post" action="login.php">
                <div class="segment">
                    <h1>Sign In</h1>
                </div>
                <label>
                    <input type="email" placeholder="Email Address" id="emailInput" name="emailInput" required>
                </label>
                <label>
                    <input type="password" placeholder="Password" id="passwordInput" name="passwordInput" required>
                </label>
                <div class="input-group">
                    <select id="roleSelect" name="roleSelect" required>
                        <option value="">Select Role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
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
    <div id="registrationPopup" class="popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <form id="registrationForm" method="post" action="register.php">
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
    </script>

    <?php
    require_once 'config.php';

    // Login form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["emailInput"])) {
        $email = $_POST["emailInput"];
        $password = $_POST["passwordInput"];
        $role = $_POST["roleSelect"];

        $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["role"] == $role) {
                session_start();
                $_SESSION["user_name"] = $row["name"];
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["role"] = $row["role"];
                if ($role == "admin") {
                    header("Location: admin.php");
                } else {
                    header("Location: user.php");
                }
                exit;
            } else {
                echo "Invalid role.";
            }
        } else {
            echo "Invalid email or password.";
        }
    }

    // Registration form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nameInput"])) {
        $name = $_POST["nameInput"];
        $email = $_POST["emailRegInput"];
        $password = $_POST["passwordRegInput"];
        $role = $_POST["roleRegSelect"];

        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    ?>
</body>

</html>