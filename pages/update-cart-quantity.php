<?php

require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

if (!isset($_GET["id"]) || !isset($_GET["action"])) {
    header("Location: " . BASE_URL . "pages/cart.php");
    exit();
}

$user_id = (int) $_SESSION["user_id"];
$cart_id = (int) $_GET["id"];
$action = $_GET["action"];

$query = "
    SELECT
        cart.quantity,
        products.stock_quantity
    FROM cart
    INNER JOIN products
        ON cart.product_id = products.id
    WHERE cart.id = $cart_id
    AND cart.user_id = $user_id
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: " . BASE_URL . "pages/cart.php");
    exit();
}

$item = mysqli_fetch_assoc($result);

$currentQuantity = $item["quantity"];
$stockQuantity = $item["stock_quantity"];

if ($action == "increase") {

    if ($currentQuantity < $stockQuantity) {
        $update = "
            UPDATE cart
            SET quantity = quantity + 1
            WHERE id = $cart_id
            AND user_id = $user_id
        ";

        mysqli_query($conn, $update);
    }

} elseif ($action == "decrease") {

    if ($currentQuantity > 1) {
        $update = "
            UPDATE cart
            SET quantity = quantity - 1
            WHERE id = $cart_id
            AND user_id = $user_id
        ";

        mysqli_query($conn, $update);
    }

}

header("Location: " . BASE_URL . "pages/cart.php");
exit();