<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";

$sort = isset($_GET["sort"]) ? $_GET["sort"] : "newest";

$orderBy = "products.created_at DESC";

switch ($sort) {
    case "price_low":
        $orderBy = "products.price ASC";
        break;

    case "price_high":
        $orderBy = "products.price DESC";
        break;

    case "name_az":
        $orderBy = "products.name ASC";
        break;

    case "name_za":
        $orderBy = "products.name DESC";
        break;

    case "oldest":
        $orderBy = "products.created_at ASC";
        break;

    default:
        $orderBy = "products.created_at DESC";
}

$query = "
    SELECT products.*, categories.name AS category_name
    FROM products
    INNER JOIN categories
    ON products.category_id = categories.id
    ORDER BY $orderBy
";

$result = mysqli_query($conn, $query);
?>

<section class="catalog-page">

    <div class="catalog-header">
        <h1>Store Catalog</h1>
        <p>Browse all available products in HyperCore.</p>
    </div>

    <form method="GET" class="sort-form">

        <label for="sort">Sort By:</label>

        <select name="sort" id="sort" onchange="this.form.submit()">

            <option value="newest" <?php if ($sort == "newest") echo "selected"; ?>>
                Newest
            </option>

            <option value="oldest" <?php if ($sort == "oldest") echo "selected"; ?>>
                Oldest
            </option>

            <option value="price_low" <?php if ($sort == "price_low") echo "selected"; ?>>
                Price Low → High
            </option>

            <option value="price_high" <?php if ($sort == "price_high") echo "selected"; ?>>
                Price High → Low
            </option>

            <option value="name_az" <?php if ($sort == "name_az") echo "selected"; ?>>
                Name A → Z
            </option>

            <option value="name_za" <?php if ($sort == "name_za") echo "selected"; ?>>
                Name Z → A
            </option>

        </select>

    </form>

    <div class="catalog-grid">

        <?php while ($product = mysqli_fetch_assoc($result)) { ?>

            <div class="catalog-card">

                <?php if ($product['is_featured'] == 1) { ?>
                    <span class="featured-badge">
                        Featured
                    </span>
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

                    <h3>
                        <?php echo $product['name']; ?>
                    </h3>

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

    </div>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>