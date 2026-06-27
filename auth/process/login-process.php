<?php

require_once __DIR__ . "/../../config/config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST["email"]);
$password = $_POST["password"];

$query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("Invalid email or password.");
}

$user = mysqli_fetch_assoc($result);

if ($user["locked_until"] !== NULL && strtotime($user["locked_until"]) > time()) {
    die("Too many failed login attempts. Please try again later.");
}

if ($user["locked_until"] !== NULL && strtotime($user["locked_until"]) <= time()) {
    $resetQuery = "
        UPDATE users
        SET failed_attempts = 0, locked_until = NULL
        WHERE id = {$user['id']}
    ";
    mysqli_query($conn, $resetQuery);

    $user["failed_attempts"] = 0;
    $user["locked_until"] = NULL;
}

if (!password_verify($password, $user["password"])) {

    $failedAttempts = $user["failed_attempts"] + 1;

    if ($failedAttempts >= 3) {
        $lockQuery = "
            UPDATE users
            SET failed_attempts = $failedAttempts,
                locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
            WHERE id = {$user['id']}
        ";

        mysqli_query($conn, $lockQuery);

        die("Too many failed login attempts. Your account is locked for 15 minutes.");
    }

    $updateQuery = "
        UPDATE users
        SET failed_attempts = $failedAttempts
        WHERE id = {$user['id']}
    ";

    mysqli_query($conn, $updateQuery);

    die("Invalid email or password. Attempt $failedAttempts of 3.");
}

$resetQuery = "
    UPDATE users
    SET failed_attempts = 0,
        locked_until = NULL
    WHERE id = {$user['id']}
";

mysqli_query($conn, $resetQuery);

$_SESSION["user_id"] = $user["id"];
$_SESSION["full_name"] = $user["full_name"];
$_SESSION["email"] = $user["email"];
$_SESSION["role"] = $user["role"];

if ($user["role"] == "admin") {
    header("Location: " . BASE_URL . "admin/dashboard.php");
    exit();
}

header("Location: " . BASE_URL . "pages/profile.php");
exit();

?>