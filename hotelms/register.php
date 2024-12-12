<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotelms";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = ""; // Initialize a variable for the alert message

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Do not escape password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password
    $created_at = date('Y-m-d H:i:s');

    // Prepare and execute the SQL query
    $sql = "INSERT INTO user (name, username, email, password, created_at) 
            VALUES ('$name', '$username', '$email', '$hashed_password', '$created_at')";

    if (mysqli_query($conn, $sql)) {
        $message = "success"; // Registration successful
    } else {
        $message = "error"; // Registration failed
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css" />
    <title>Register Admin</title>
</head>
<body>
    <div class="container">
        <div class="card" style="max-width: 400px; margin: 50px auto; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <h2 class="text-center">Register Admin</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-link">Back to Login</a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if ($message === "success") : ?>
                alert("Admin registered successfully!");
            <?php elseif ($message === "error") : ?>
                alert("Error occurred during registration. Please try again.");
            <?php endif; ?>
        });
    </script>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/validator.min.js"></script>
</body>
</html>
