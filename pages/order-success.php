<?php

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}
?>

<section class="success-page">

    <div class="success-box">

        <h1>Order Placed Successfully!</h1>

        <p>
            Thank you for shopping at HyperCore.
        </p>

        <p>
            Your order has been received and is currently pending.
        </p>

        <a
            href="<?php echo BASE_URL; ?>pages/catalog.php"
            class="checkout-btn"
        >
            Continue Shopping
        </a>

    </div>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>