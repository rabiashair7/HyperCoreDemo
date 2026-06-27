<?php

require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: " . BASE_URL . "pages/catalog.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = intval($_GET["id"]);

$productQuery = "
    SELECT stock_quantity
    FROM products
    WHERE id = $product_id
";

$productResult = mysqli_query($conn, $productQuery);

if (mysqli_num_rows($productResult) == 0) {
    header("Location: " . BASE_URL . "pages/catalog.php");
    exit();
}

$product = mysqli_fetch_assoc($productResult);
$stock_quantity = $product["stock_quantity"];

if ($stock_quantity <= 0) {
    header("Location: " . BASE_URL . "pages/product-details.php?id=" . $product_id);
    exit();
}

$checkQuery = "
    SELECT quantity
    FROM cart
    WHERE user_id = $user_id
    AND product_id = $product_id
";

$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {

    $cartItem = mysqli_fetch_assoc($checkResult);
    $current_quantity = $cartItem["quantity"];

    if ($current_quantity < $stock_quantity) {
        $updateQuery = "
            UPDATE cart
            SET quantity = quantity + 1
            WHERE user_id = $user_id
            AND product_id = $product_id
        ";

        mysqli_query($conn, $updateQuery);
    }

} else {

    $insertQuery = "
        INSERT INTO cart
        (user_id, product_id, quantity)
        VALUES
        ($user_id, $product_id, 1)
    ";

    mysqli_query($conn, $insertQuery);
}

header("Location: " . BASE_URL . "pages/cart.php");
exit();