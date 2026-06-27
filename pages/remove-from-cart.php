<?php

require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: " . BASE_URL . "pages/cart.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$cart_id = intval($_GET["id"]);

$query = "
    DELETE FROM cart
    WHERE id = $cart_id
    AND user_id = $user_id
";

mysqli_query($conn, $query);

header("Location: " . BASE_URL . "pages/cart.php");
exit();