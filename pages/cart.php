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
        cart.id AS cart_id,
        cart.quantity,
        products.id,
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

$total = 0;
$stockProblem = false;
?>

<section class="cart-page">

    <div class="cart-header">
        <h1>Your Cart</h1>
        <p>Review your selected products before checkout.</p>
    </div>

    <?php if ($result && mysqli_num_rows($result) > 0) { ?>

        <div class="cart-layout">

            <div class="cart-items">

                <?php while ($item = mysqli_fetch_assoc($result)) { ?>

                    <?php
                        $subtotal = $item["price"] * $item["quantity"];
                        $total += $subtotal;

                        if ($item["quantity"] > $item["stock_quantity"]) {
                            $stockProblem = true;
                        }
                    ?>

                    <div class="cart-card">

                        <div class="cart-image-box">
                            <img
                                src="<?php echo BASE_URL . htmlspecialchars($item["image"]); ?>"
                                alt="<?php echo htmlspecialchars($item["name"]); ?>"
                            >
                        </div>

                        <div class="cart-info">

                            <h3>
                                <?php echo htmlspecialchars($item["name"]); ?>
                            </h3>

                            <p class="cart-price">
                                ₪<?php echo number_format($item["price"], 2); ?>
                            </p>

                            <p class="cart-stock">
                                Available Stock:
                                <span><?php echo $item["stock_quantity"]; ?></span>
                            </p>

                            <?php if ($item["quantity"] > $item["stock_quantity"]) { ?>
                                <p class="stock-error">
                                    Not enough stock available.
                                </p>
                            <?php } ?>

                            <div class="cart-quantity">

                                <span class="quantity-label">Quantity</span>

                                <div class="quantity-controls">

                                    <a
                                        href="<?php echo BASE_URL; ?>pages/update-cart-quantity.php?id=<?php echo $item["cart_id"]; ?>&action=decrease"
                                        class="qty-btn"
                                    >
                                        -
                                    </a>

                                    <span class="qty-number">
                                        <?php echo $item["quantity"]; ?>
                                    </span>

                                    <a
                                        href="<?php echo BASE_URL; ?>pages/update-cart-quantity.php?id=<?php echo $item["cart_id"]; ?>&action=increase"
                                        class="qty-btn"
                                    >
                                        +
                                    </a>

                                </div>

                            </div>

                        </div>

                        <div class="cart-actions">

                            <p class="cart-subtotal">
                                Subtotal
                                <span>
                                    ₪<?php echo number_format($subtotal, 2); ?>
                                </span>
                            </p>

                            <a
                                href="<?php echo BASE_URL; ?>pages/remove-from-cart.php?id=<?php echo $item["cart_id"]; ?>"
                                class="remove-btn"
                            >
                                Remove
                            </a>

                        </div>

                    </div>

                <?php } ?>

            </div>

            <div class="cart-total">

                <h2>Order Summary</h2>

                <div class="summary-row">
                    <span>Total</span>
                    <strong>
                        ₪<?php echo number_format($total, 2); ?>
                    </strong>
                </div>

                <?php if ($stockProblem == false) { ?>

                    <a
                        href="<?php echo BASE_URL; ?>pages/checkout.php"
                        class="checkout-btn"
                    >
                        Proceed To Checkout
                    </a>

                <?php } else { ?>

                    <p class="stock-error">
                        Please fix stock issues before checkout.
                    </p>

                <?php } ?>

            </div>

        </div>

    <?php } else { ?>

        <div class="empty-cart">

            <h2>Your cart is empty.</h2>

            <p>
                Looks like you have not added any products yet.
            </p>

            <a
                href="<?php echo BASE_URL; ?>pages/catalog.php"
                class="browse-btn"
            >
                Browse Products
            </a>

        </div>

    <?php } ?>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>