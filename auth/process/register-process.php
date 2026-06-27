<?php
require_once __DIR__ . "/../../config/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "auth/register.php");
    exit();
}

$full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

$check_query = "SELECT id FROM users WHERE email = '$email'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    die("Email already exists.");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$insert_query = "
    INSERT INTO users (full_name, email, password, role)
    VALUES ('$full_name', '$email', '$hashed_password', 'user')
";

if (mysqli_query($conn, $insert_query)) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
} else {
    die("Register failed: " . mysqli_error($conn));
}