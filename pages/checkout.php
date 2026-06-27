<?php

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$user_id = (int) $_SESSION["user_id"];

$query = "
    SELECT
        cart.quantity,
        products.name,
        products.price,
        products.image,
        products.stock_quantity
    FROM cart
    INNER JOIN products
        ON cart.product_id = products.id
    WHERE cart.user_id = $user_id
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: " . BASE_URL . "pages/cart.php");
    exit();
}

$total = 0;
$stockProblem = false;
?>

<section class="checkout-page">

    <div class="checkout-box">

        <div class="checkout-header">
            <span class="checkout-badge">Secure Checkout</span>
            <h1>Review Your Order</h1>
            <p>Check your products before placing your HyperCore order.</p>
        </div>

        <div class="checkout-layout">

            <div class="checkout-items">

                <?php while ($item = mysqli_fetch_assoc($result)) { ?>

                    <?php
                        $subtotal = $item["price"] * $item["quantity"];
                        $total += $subtotal;

                        if ($item["quantity"] > $item["stock_quantity"]) {
                            $stockProblem = true;
                        }
                    ?>

                    <div class="checkout-item">

                        <div class="checkout-image-box">
                            <img
                                src="<?php echo BASE_URL . htmlspecialchars($item["image"]); ?>"
                                alt="<?php echo htmlspecialchars($item["name"]); ?>"
                            >
                        </div>

                        <div class="checkout-item-info">

                            <h3>
                                <?php echo htmlspecialchars($item["name"]); ?>
                            </h3>

                            <div class="checkout-details">

                                <div class="checkout-row">
                                    <span>Price</span>
                                    <strong>
                                        ₪<?php echo number_format($item["price"], 2); ?>
                                    </strong>
                                </div>

                                <div class="checkout-row">
                                    <span>Quantity</span>
                                    <strong>
                                        <?php echo $item["quantity"]; ?>
                                    </strong>
                                </div>

                                <div class="checkout-row">
                                    <span>Available Stock</span>
                                    <strong>
                                        <?php echo $item["stock_quantity"]; ?>
                                    </strong>
                                </div>

                                <div class="checkout-row">
                                    <span>Subtotal</span>
                                    <strong>
                                        ₪<?php echo number_format($subtotal, 2); ?>
                                    </strong>
                                </div>

                            </div>

                            <?php if ($item["quantity"] > $item["stock_quantity"]) { ?>
                                <p class="stock-error">
                                    Not enough stock available.
                                </p>
                            <?php } ?>

                        </div>

                    </div>

                <?php } ?>

            </div>

            <div class="checkout-summary-card">

                <h2>Order Summary</h2>

                <div class="summary-line">
                    <span>Total</span>
                    <strong>
                        ₪<?php echo number_format($total, 2); ?>
                    </strong>
                </div>

                <?php if ($stockProblem) { ?>

                    <p class="stock-error checkout-warning">
                        Some products are no longer available in the requested quantity.
                        Please go back to your cart and update them.
                    </p>

                <?php } ?>

                <div class="checkout-actions">

                    <a
                        href="<?php echo BASE_URL; ?>pages/cart.php"
                        class="back-cart-btn"
                    >
                        Back To Cart
                    </a>

                    <?php if (!$stockProblem) { ?>

                        <a
                            href="<?php echo BASE_URL; ?>pages/place-order.php"
                            class="checkout-btn"
                        >
                            Place Order
                        </a>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>