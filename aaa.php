<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="home.css">
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
        <li><a href="#" aria-current="page">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
    <div class="cta-buttons">
      <a href="#" class="cta">Logout</a>
    </div>
  </header>

  <main class="admin-dashboard">
    <section class="user-details">
      <h2>User Details</h2>
      <div class="user-list">
        <?php
        // Fetch and display user details from the database
        $users = getAllUsers();
        foreach ($users as $user) {
          echo '<div class="user">';
          echo '<span>Name: ' . $user['name'] . '</span>';
          echo '<span>Email: ' . $user['email'] . '</span>';
          echo '<button class="remove-btn" onclick="removeUser(' . $user['id'] . ')">Remove</button>';
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
        // Fetch and display hotel booking details from the database
        $bookings = getAllBookings();
        foreach ($bookings as $booking) {
          echo '<div class="booking">';
          echo '<span>Name: ' . $booking['name'] . '</span>';
          echo '<span>Date: ' . $booking['date'] . '</span>';
          echo '<span>Guests: ' . $booking['guests'] . '</span>';
          echo '<span>Total Cost: $' . $booking['total_cost'] . '</span>';
          echo '</div>';
        }
        ?>
      </div>
    </section>
  </main>

  <div id="addUserModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeAddUserModal()">&times;</span>
      <h2>Add New User</h2>
      <form action="add_user.php" method="post">
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
        <button type="submit" class="submit-btn">Add User</button>
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
      // Call a PHP function to remove the user from the database
      removeUserFromDB(userId);
      // Refresh the user list or show a success message
    }
  </script>

  <?php
  // PHP functions to interact with the database
  function getAllUsers() {
    // Fetch all users from the database and return the data
    return $users;
  }

  function getAllBookings() {
    // Fetch all hotel bookings from the database and return the data
    return $bookings;
  }

  function removeUserFromDB($userId) {
    // Remove the user from the database based on the provided user ID
  }

  function addUserToDB($name, $email, $password, $role) {
    // Add the new user to the database
  }
  ?>
</body>
</html>