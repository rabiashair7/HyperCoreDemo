<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/admin-check.php";
require_once __DIR__ . "/../assets/js/classes/Product.php";

if (!isset($_GET["id"])) {
    header("Location: " . BASE_URL . "admin/products.php");
    exit();
}

$product_id = $_GET["id"];
$product = Product::getProductById($conn, $product_id);

if (!$product) {
    header("Location: " . BASE_URL . "admin/products.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $price = $_POST["price"];
    $stock_quantity = $_POST["stock_quantity"];
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;

    $image = $product["image"];

    if (!empty($_FILES["image"]["name"])) {
        $image_name = time() . "_" . $_FILES["image"]["name"];
        $target_path = __DIR__ . "/../assets/images/products/" . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            $image = "assets/images/products/" . $image_name;
        }
    }

    $updated = Product::updateProduct(
        $conn,
        $product_id,
        $category_id,
        $name,
        $description,
        $price,
        $image,
        $stock_quantity,
        $is_featured
    );

    if ($updated) {
        header("Location: " . BASE_URL . "admin/products.php");
        exit();
    } else {
        $message = "Failed to update product.";
    }
}

$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);
?>

<section class="admin-page">

    <div class="admin-header">
        <h1>Edit Product</h1>
        <p>Update product information.</p>
    </div>

    <?php if (!empty($message)) { ?>
        <p class="admin-error"><?php echo $message; ?></p>
    <?php } ?>

    <form class="admin-form" method="POST" enctype="multipart/form-data">

        <label>Product Name</label>
        <input 
            type="text" 
            name="name" 
            value="<?php echo htmlspecialchars($product["name"]); ?>" 
            required
        >

        <label>Description</label>
        <textarea name="description" required><?php echo htmlspecialchars($product["description"]); ?></textarea>

        <label>Category</label>
        <select name="category_id" required>
            <?php while ($category = mysqli_fetch_assoc($categories_result)) { ?>
                <option 
                    value="<?php echo $category["id"]; ?>"
                    <?php if ($category["id"] == $product["category_id"]) echo "selected"; ?>
                >
                    <?php echo $category["name"]; ?>
                </option>
            <?php } ?>
        </select>

        <label>Price</label>
        <input 
            type="number" 
            name="price" 
            step="0.01" 
            value="<?php echo $product["price"]; ?>" 
            required
        >

        <label>Stock Quantity</label>
        <input 
            type="number" 
            name="stock_quantity" 
            min="0" 
            value="<?php echo $product["stock_quantity"]; ?>" 
            required
        >

        <label>Current Image</label>
        <img 
            src="<?php echo BASE_URL . $product["image"]; ?>" 
            alt="<?php echo htmlspecialchars($product["name"]); ?>" 
            class="admin-current-image"
        >

        <label>Change Image</label>

<div class="file-upload-wrapper">

    <label for="image" class="file-upload-btn">
        Choose New Image
    </label>

    <input
        type="file"
        id="image"
        name="image"
        accept="image/*"
    >

    <span id="file-name">
        No new file selected
    </span>

</div>

        <label class="checkbox-label">
            <input 
                type="checkbox" 
                name="is_featured"
                <?php if ($product["is_featured"] == 1) echo "checked"; ?>
            >
            Featured Product
        </label>

        <button type="submit" class="admin-btn">
            Update Product
        </button>

        <a href="<?php echo BASE_URL; ?>admin/products.php" class="admin-cancel-btn">
            Cancel
        </a>

    </form>

</section>
<script>
document.getElementById("image").addEventListener("change", function () {
    let fileName = "No new file selected";

    if (this.files.length > 0) {
        fileName = this.files[0].name;
    }

    document.getElementById("file-name").textContent = fileName;
});
</script>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>