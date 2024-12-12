<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "hotelms");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the email exists in the database
    $sql = "SELECT password FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Verify the current password
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if (password_verify($current_password, $db_password)) {
            // Update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE user SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashed_password, $email);

            if ($update_stmt->execute()) {
                echo "Password updated successfully!";
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "Email not found.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <title>Forgot Password</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(#dce6ed);
        }
        .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-center text-center">
                <h2>Forgot Password</h2>
            </div>
            <div class="card-body">
                <form action="forgot_password.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="new_password">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="login.php" class="btn btn-link">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        // Display alerts based on PHP message
        document.addEventListener("DOMContentLoaded", function () {
            const message = "<?php echo $message; ?>";
            if (message === "success") {
                alert("Password updated successfully!");
                window.location.href = "login.php";
            } else if (message === "error") {
                alert("Error occurred during password update. Please try again.");
            } else if (message === "incorrect_password") {
                alert("Current password is incorrect.");
            } else if (message === "email_not_found") {
                alert("Email not found.");
            }
        });
    </script>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
