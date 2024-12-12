<?php
session_start();
require_once "db.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($new_password !== $confirm_password) {
        echo "New password and confirm password do not match!";
        exit();
    }

    // Fetch current password from database
    $sql = "SELECT password FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }

    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        echo "Current password is incorrect.";
        exit();
    }

    // Update password in the database
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password = :password WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':password' => $hashed_password,
        ':user_id' => $user_id
    ]);

    echo "Password successfully changed!";
    header("Location: login.php"); // Redirect to login page after success
    exit();
}
?>
