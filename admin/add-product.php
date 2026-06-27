<?php

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/admin-check.php";
require_once __DIR__ . "/../assets/js/classes/Product.php";
$message = "";

$categoriesQuery = "SELECT * FROM categories";
$categoriesResult = mysqli_query($conn, $categoriesQuery);

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $category_id = $_POST["category_id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $image = "";
        if (!empty($_FILES["image"]["name"])) {
    $imageName = time() . "_" . $_FILES["image"]["name"];
    $targetPath = __DIR__ . "/../assets/images/products/" . $imageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
        $image = "assets/images/products/" . $imageName;
    }
}    $stock_quantity = $_POST["stock_quantity"];
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;

    $product = new Product(
        $category_id,
        $name,
        $description,
        $price,
        $image,
        $stock_quantity,
        $is_featured
    );

    if ($product->isProductExist($conn))
    {
        $message = "
            <div class='error-message'>
                Product already exists.
            </div>
        ";
    }
    else
    {
        if ($product->addProduct($conn))
        {
            $message = "
                <div class='success-message'>
                    Product added successfully.
                </div>
            ";
        }
        else
        {
            $message = "
                <div class='error-message'>
                    Failed to add product.
                </div>
            ";
        }
    }
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";
?>

<link rel="stylesheet"
      href="<?php echo BASE_URL; ?>assets/css/admin.css">

<section class="admin-page">

    <div class="admin-container">

        <h1>Add Product</h1>

        <?php echo $message; ?>

       <form method="POST" class="admin-form" enctype="multipart/form-data">

            <label>Category</label>

            <select name="category_id" required>

                <option value="">
                    Select Category
                </option>

                <?php while($category = mysqli_fetch_assoc($categoriesResult)) { ?>

                    <option value="<?php echo $category["id"]; ?>">
                        <?php echo $category["name"]; ?>
                    </option>

                <?php } ?>

            </select>

            <label>Product Name</label>

            <input
                type="text"
                name="name"
                required
            >

            <label>Description</label>

            <textarea
                name="description"
                rows="5"
                required
            ></textarea>

            <label>Price</label>

            <input
                type="number"
                step="0.01"
                name="price"
                required
            >

      <label>Product Image</label>

<div class="file-upload-wrapper">

    <label for="image" class="file-upload-btn">
        Choose Product Image
    </label>

    <input
        type="file"
        id="image"
        name="image"
        accept="image/*"
        required
    >

    <span id="file-name">
        No file selected
    </span>

</div>

            <label>Stock Quantity</label>

            <input
                type="number"
                name="stock_quantity"
                min="0"
                required
            >

            <div class="checkbox-group">

                <input
                    type="checkbox"
                    name="is_featured"
                    id="featured"
                >

                <label for="featured">
                    Featured Product
                </label>

            </div>

            <button type="submit">
                Add Product
            </button>

        </form>

    </div>

</section>
<script>
document.getElementById("image").addEventListener("change", function () {
    let fileName = "No file selected";

    if (this.files.length > 0) {
        fileName = this.files[0].name;
    }

    document.getElementById("file-name").textContent = fileName;
});
</script>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>