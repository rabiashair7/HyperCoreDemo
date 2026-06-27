<?php

class Product
{
    public $category_id;
    public $name;
    public $description;
    public $price;
    public $image;
    public $stock_quantity;
    public $is_featured;

    public function __construct(
        $category_id,
        $name,
        $description,
        $price,
        $image,
        $stock_quantity,
        $is_featured
    ) {
        $this->category_id = $category_id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->stock_quantity = $stock_quantity;
        $this->is_featured = $is_featured;
    }

    public function isProductExist($conn)
    {
        $name = mysqli_real_escape_string($conn, $this->name);

        $query = "
            SELECT id
            FROM products
            WHERE name = '$name'
            LIMIT 1
        ";

        $result = mysqli_query($conn, $query);

        return mysqli_num_rows($result) > 0;
    }

    public function addProduct($conn)
    {
        $category_id = (int)$this->category_id;

        $name = mysqli_real_escape_string($conn, $this->name);

        $description = mysqli_real_escape_string(
            $conn,
            $this->description
        );

        $price = (float)$this->price;

        $image = mysqli_real_escape_string(
            $conn,
            $this->image
        );

        $stock_quantity = (int)$this->stock_quantity;

        $is_featured = (int)$this->is_featured;

        $query = "
            INSERT INTO products
            (
                category_id,
                name,
                description,
                price,
                image,
                stock_quantity,
                is_featured
            )
            VALUES
            (
                '$category_id',
                '$name',
                '$description',
                '$price',
                '$image',
                '$stock_quantity',
                '$is_featured'
            )
        ";

        return mysqli_query($conn, $query);
    }
 public static function getTotalProducts($conn)
{
    $query = "SELECT COUNT(*) AS total FROM products";

    $result = mysqli_query($conn, $query);

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}
public static function getAllProducts($conn)
{
    $query = "
        SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN categories
        ON products.category_id = categories.id
        ORDER BY products.id DESC
    ";

    return mysqli_query($conn, $query);
}
public static function getProductById($conn, $id)
{
    $id = (int)$id;

    $query = "
        SELECT *
        FROM products
        WHERE id = '$id'
        LIMIT 1
    ";

    $result = mysqli_query($conn, $query);

    return mysqli_fetch_assoc($result);
}
public static function updateProduct(
    $conn,
    $id,
    $category_id,
    $name,
    $description,
    $price,
    $image,
    $stock_quantity,
    $is_featured
)
{
    $id = (int)$id;
    $category_id = (int)$category_id;

    $name = mysqli_real_escape_string($conn, $name);

    $description = mysqli_real_escape_string(
        $conn,
        $description
    );

    $price = (float)$price;

    $image = mysqli_real_escape_string(
        $conn,
        $image
    );

    $stock_quantity = (int)$stock_quantity;
    $is_featured = (int)$is_featured;

    $query = "
        UPDATE products
        SET
            category_id = '$category_id',
            name = '$name',
            description = '$description',
            price = '$price',
            image = '$image',
            stock_quantity = '$stock_quantity',
            is_featured = '$is_featured'
        WHERE id = '$id'
    ";

    return mysqli_query($conn, $query);
}
public static function deleteProduct($conn, $id)
{
    $id = (int)$id;

    $deleteCartQuery = "
        DELETE FROM cart
        WHERE product_id = $id
    ";

    mysqli_query($conn, $deleteCartQuery);

    $deleteProductQuery = "
        DELETE FROM products
        WHERE id = $id
    ";

    return mysqli_query($conn, $deleteProductQuery);
}
}