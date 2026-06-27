<?php

require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$user_id = (int) $_SESSION["user_id"];

$cartQuery = "
    SELECT
        cart.product_id,
        cart.quantity,
        products.price,
        products.stock_quantity
    FROM cart
    INNER JOIN products
        ON cart.product_id = products.id
    WHERE cart.user_id = $user_id
";

$cartResult = mysqli_query($conn, $cartQuery);

if (mysqli_num_rows($cartResult) == 0) {
    header("Location: " . BASE_URL . "pages/cart.php");
    exit();
}

$total = 0;
$items = [];

while ($item = mysqli_fetch_assoc($cartResult)) {

    if ($item["quantity"] > $item["stock_quantity"]) {
        header("Location: " . BASE_URL . "pages/cart.php");
        exit();
    }

    $items[] = $item;
    $total += $item["price"] * $item["quantity"];
}

$orderQuery = "
    INSERT INTO orders
    (user_id, total_price, status)
    VALUES
    ($user_id, $total, 'pending')
";

mysqli_query($conn, $orderQuery);

$order_id = mysqli_insert_id($conn);

foreach ($items as $item) {

    $product_id = $item["product_id"];
    $quantity = $item["quantity"];
    $price = $item["price"];

    $orderItemQuery = "
        INSERT INTO order_items
        (order_id, product_id, quantity, price)
        VALUES
        ($order_id, $product_id, $quantity, $price)
    ";

    mysqli_query($conn, $orderItemQuery);

    $stockUpdate = "
      UPDATE products
    SET stock_quantity = stock_quantity - $quantity
    WHERE id = $product_id
    AND stock_quantity >= $quantity
    ";

    mysqli_query($conn, $stockUpdate);
}

$clearCart = "
    DELETE FROM cart
    WHERE user_id = $user_id
";

mysqli_query($conn, $clearCart);

header("Location: " . BASE_URL . "pages/order-success.php");
exit();