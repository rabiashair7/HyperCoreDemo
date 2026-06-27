<?php

require_once __DIR__ . "/../config/config.php";

if (!isset($_GET["id"])) {
    die("Product not found.");
}

$product_id = (int) $_GET["id"];

$query = "
    SELECT products.*, categories.name AS category_name
    FROM products
    INNER JOIN categories
    ON products.category_id = categories.id
    WHERE products.id = $product_id
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($result);



$recentProducts = [];

if (isset($_COOKIE["recently_viewed"])) {
    $recentProducts = explode(",", $_COOKIE["recently_viewed"]);
}

$recentProducts = array_diff($recentProducts, [$product_id]);
array_unshift($recentProducts, $product_id);

$recentProducts = array_slice($recentProducts, 0, 5);

setcookie(
    "recently_viewed",
    implode(",", $recentProducts),
    time() + (86400 * 30),
    "/"
);

/* Get recently viewed products except current product */

$recentIds = array_filter($recentProducts, function ($id) use ($product_id) {
    return (int)$id !== (int)$product_id;
});

$recentResult = false;

if (!empty($recentIds)) {
    $ids = implode(",", array_map("intval", $recentIds));

    $recentQuery = "
        SELECT *
        FROM products
        WHERE id IN ($ids)
    ";

    $recentResult = mysqli_query($conn, $recentQuery);
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";
?>

<section class="product-page">

    <div class="product-container">

        <div class="product-image">

            <img
                src="<?php echo BASE_URL . $product["image"]; ?>"
                alt="<?php echo $product["name"]; ?>"
            >

        </div>

        <div class="product-info">

            <h1>
                <?php echo $product["name"]; ?>
            </h1>

            <p class="product-category">
                Category:
                <?php echo $product["category_name"]; ?>
            </p>

            <p class="product-price">
                $<?php echo number_format($product["price"], 2); ?>
            </p>

            <?php if ($product["stock_quantity"] > 0) { ?>

                <p class="product-stock">
                    In Stock:
                    <?php echo $product["stock_quantity"]; ?>
                </p>

            <?php } else { ?>

                <p class="product-stock out-of-stock">
                    Out of Stock
                </p>

            <?php } ?>

            <p class="product-description">
                <?php echo $product["description"]; ?>
            </p>

            <div class="product-buttons">

                <?php if ($product["stock_quantity"] > 0) { ?>

                    <a
                        href="<?php echo BASE_URL; ?>pages/add-to-cart.php?id=<?php echo $product["id"]; ?>"
                        class="add-cart-btn"
                    >
                        Add To Cart
                    </a>

                <?php } else { ?>

                    <button class="add-cart-btn out-stock-btn" disabled>
                        Out of Stock
                    </button>

                <?php } ?>

            </div>

        </div>

    </div>

    <?php if ($recentResult && mysqli_num_rows($recentResult) > 0) { ?>

        <div class="recently-viewed">

            <h2>Recently Viewed Products</h2>

            <div class="recently-viewed-grid">

                <?php while ($recent = mysqli_fetch_assoc($recentResult)) { ?>

                    <div class="recent-card">

                        <img
                            src="<?php echo BASE_URL . $recent["image"]; ?>"
                            alt="<?php echo $recent["name"]; ?>"
                        >

                        <h3><?php echo $recent["name"]; ?></h3>

                        <p>
                            $<?php echo number_format($recent["price"], 2); ?>
                        </p>

                        <a
                            href="<?php echo BASE_URL; ?>pages/product-details.php?id=<?php echo $recent["id"]; ?>"
                            class="recent-btn"
                        >
                            View Details
                        </a>

                    </div>

                <?php } ?>

            </div>

        </div>

    <?php } ?>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>