<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";

$search = "";

if (isset($_GET["search"])) {
    $search = mysqli_real_escape_string($conn, $_GET["search"]);
}

$query = "
    SELECT products.*, categories.name AS category_name
    FROM products
    INNER JOIN categories
    ON products.category_id = categories.id
    WHERE products.name LIKE '%$search%'
";

$result = mysqli_query($conn, $query);
?>

<section class="catalog-page">

    <div class="catalog-header">
        <h1>Search Results</h1>
        <p>Results for: <?php echo htmlspecialchars($search); ?></p>
    </div>

    <div class="catalog-grid">

        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

            <?php while ($product = mysqli_fetch_assoc($result)) { ?>

                <div class="catalog-card">

                    <?php if ($product['is_featured'] == 1) { ?>
                        <span class="featured-badge">Featured</span>
                    <?php } ?>

                    <div class="catalog-image-box">
                        <img
                            src="<?php echo BASE_URL . $product['image']; ?>"
                            alt="<?php echo $product['name']; ?>"
                            class="catalog-image"
                        >
                    </div>

                    <div class="catalog-info">

                        <span class="catalog-category">
                            <?php echo $product['category_name']; ?>
                        </span>

                        <h3><?php echo $product['name']; ?></h3>

                        <p class="catalog-price">
                            ₪<?php echo $product['price']; ?>
                        </p>

                        <p class="catalog-stock">
                            Stock: <?php echo $product['stock_quantity']; ?>
                        </p>

                        <div class="catalog-actions">

                            <a
                                href="<?php echo BASE_URL; ?>pages/product-details.php?id=<?php echo $product['id']; ?>"
                                class="details-btn"
                            >
                                View Details
                            </a>

                            <a
                                href="<?php echo BASE_URL; ?>pages/add-to-cart.php?id=<?php echo $product['id']; ?>"
                                class="cart-btn"
                            >
                                Add to Cart
                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p class="empty-table-message">
                No products found.
            </p>

        <?php } ?>

    </div>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>