<?php
$query = "SELECT * FROM products WHERE is_featured = 1 LIMIT 8";
$result = mysqli_query($conn, $query);
?>

<section class="home-page">

    <section class="hero-section">
        <div class="hero-content">
            <span class="hero-badge">Premium Gaming Store</span>
            <h1>Welcome to HyperCore</h1>
            <p>
                Discover games, hardware, accessories, and retro gaming products
                in one modern gaming store.
            </p>
            <a href="<?php echo BASE_URL; ?>pages/catalog.php" class="hero-btn">
                Browse Store
            </a>
        </div>
    </section>

    <section class="featured-section">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Explore some of our newest and most popular products.</p>
        </div>

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <div class="products-grid">

                <?php while ($product = mysqli_fetch_assoc($result)) { ?>
                    <div class="product-card">

                        <?php if ($product['is_featured'] == 1) { ?>
                            <span class="featured-badge">Featured</span>
                        <?php } ?>

                        <div class="product-image-box">
                            <img
                                src="<?php echo BASE_URL . $product['image']; ?>"
                                alt="<?php echo $product['name']; ?>"
                                class="product-image"
                            >
                        </div>

                        <div class="product-info">
                            <h3 title="<?php echo $product['name']; ?>">
                                <?php echo $product['name']; ?>
                            </h3>

                            <p class="product-description">
                                <?php echo $product['description']; ?>
                            </p>

                            <p class="product-price">
                                ₪<?php echo $product['price']; ?>
                            </p>

                            <p class="product-stock">
                                Stock: <?php echo $product['stock_quantity']; ?> left
                            </p>

                            <div class="product-actions">
                                <a href="<?php echo BASE_URL; ?>pages/product-details.php?id=<?php echo $product['id']; ?>" class="details-btn">
                                    View Details
                                </a>
                            </div>
                        </div>

                    </div>
                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="empty-products">
                <h3>No Featured Products Yet</h3>
                <p>Featured products will appear here soon.</p>
            </div>

        <?php } ?>

    </section>

</section>