<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/admin-check.php";
require_once __DIR__ . "/../assets/js/classes/Product.php";

if (!isset($_GET["id"])) {
    header("Location: " . BASE_URL . "admin/products.php");
    exit();
}

$product_id = (int)$_GET["id"];
$product = Product::getProductById($conn, $product_id);

if (!$product) {
    header("Location: " . BASE_URL . "admin/products.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["confirm_delete"])) {

        if (Product::deleteProduct($conn, $product_id)) {
            header("Location: " . BASE_URL . "admin/products.php");
            exit();
        }
    }
}
?>

<section class="admin-page">

    <div class="admin-header">
        <h1>Delete Product</h1>
        <p>Are you sure you want to delete this product?</p>
    </div>

    <div class="delete-confirm-box">

        <img
            src="<?php echo BASE_URL . $product["image"]; ?>"
            alt="<?php echo htmlspecialchars($product["name"]); ?>"
            class="admin-current-image"
        >

        <h2><?php echo htmlspecialchars($product["name"]); ?></h2>

        <p>
            This action cannot be undone.
        </p>

        <form method="POST">

            <button
                type="submit"
                name="confirm_delete"
                class="delete-btn"
            >
                Yes, Delete
            </button>

            <a
                href="<?php echo BASE_URL; ?>admin/products.php"
                class="admin-cancel-btn"
            >
                Cancel
            </a>

        </form>

    </div>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>