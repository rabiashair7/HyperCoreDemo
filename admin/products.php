<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/admin-check.php";
require_once __DIR__ . "/../assets/js/classes/Product.php";
require_once __DIR__ . "/../includes/top-navbar.php";

$products = Product::getAllProducts($conn);
?>

<section class="admin-page">

    <div class="admin-header">
        <h1>Manage Products</h1>
        <p>View, edit, and delete all HyperCore products.</p>

        <a href="<?php echo BASE_URL; ?>admin/add-product.php" class="admin-btn">
            + Add New Product
        </a>
    </div>

    <div class="admin-table-container">

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($products && mysqli_num_rows($products) > 0) { ?>

                    <?php while ($product = mysqli_fetch_assoc($products)) { ?>
                        <tr>
                            <td>
                                <img 
                                    src="<?php echo BASE_URL . $product['image']; ?>" 
                                    alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                    class="admin-product-img"
                                >
                            </td>

                            <td class="product-name-cell">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </td>

                            <td>
                                ₪<?php echo number_format($product['price'], 2); ?>
                            </td>

                            <td>
                                <?php
                                if ($product['stock_quantity'] == 0) {
                                    echo "<span class='stock-out'>Out of Stock</span>";
                                } elseif ($product['stock_quantity'] <= 10) {
                                    echo "<span class='stock-low'>" . $product['stock_quantity'] . "</span>";
                                } else {
                                    echo "<span class='stock-good'>" . $product['stock_quantity'] . "</span>";
                                }
                                ?>
                            </td>

                            <td>
                                <?php if ($product['is_featured'] == 1) { ?>
                                    <span class="featured-badge-admin">Yes</span>
                                <?php } else { ?>
                                    <span class="not-featured-badge-admin">No</span>
                                <?php } ?>
                            </td>

                            <td>
                                <div class="table-actions">
                                    <a 
                                        href="<?php echo BASE_URL; ?>admin/edit-product.php?id=<?php echo $product['id']; ?>" 
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                   <a
    href="<?php echo BASE_URL; ?>admin/delete-product.php?id=<?php echo $product["id"]; ?>"
    class="delete-btn"
>
    Delete
</a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="7" class="empty-table-message">
                            No products found.
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>

    </div>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>