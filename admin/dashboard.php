<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../assets/js/classes/Product.php";

$totalProducts = Product::getTotalProducts($conn);

$usersQuery = "SELECT COUNT(*) AS total FROM users WHERE role = 'user'";
$usersResult = mysqli_query($conn, $usersQuery);
$totalUsers = mysqli_fetch_assoc($usersResult)['total'];

$ordersQuery = "SELECT COUNT(*) AS total FROM orders";
$ordersResult = mysqli_query($conn, $ordersQuery);
$totalOrders = mysqli_fetch_assoc($ordersResult)['total'];

$revenueQuery = "SELECT SUM(total_price) AS total FROM orders";
$revenueResult = mysqli_query($conn, $revenueQuery);
$totalRevenue = mysqli_fetch_assoc($revenueResult)['total'];

if ($totalRevenue == NULL) {
    $totalRevenue = 0;
}

$lowStockQuery = "
    SELECT *
    FROM products
    WHERE stock_quantity > 0 AND stock_quantity <= 10
    ORDER BY stock_quantity ASC
    LIMIT 5
";
$lowStockProducts = mysqli_query($conn, $lowStockQuery);

$outOfStockQuery = "
    SELECT *
    FROM products
    WHERE stock_quantity = 0
    ORDER BY name ASC
    LIMIT 5
";
$outOfStockProducts = mysqli_query($conn, $outOfStockQuery);

$recentOrdersQuery = "
    SELECT orders.*, users.full_name
    FROM orders
    INNER JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
    LIMIT 5
";
$recentOrders = mysqli_query($conn, $recentOrdersQuery);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";
?>

<section class="admin-dashboard">

    <div class="admin-header">
        <h1>Admin Dashboard</h1>
        <p>Manage HyperCore products, users, orders, and store statistics.</p>
    </div>

    <div class="admin-stats-grid">

        <div class="admin-stat-card">
            <h3>Total Products</h3>
            <p><?php echo $totalProducts; ?></p>
        </div>

        <div class="admin-stat-card">
            <h3>Total Users</h3>
            <p><?php echo $totalUsers; ?></p>
        </div>

        <div class="admin-stat-card">
            <h3>Total Orders</h3>
            <p><?php echo $totalOrders; ?></p>
        </div>

        <div class="admin-stat-card">
            <h3>Total Revenue</h3>
            <p>₪<?php echo number_format($totalRevenue, 2); ?></p>
        </div>

    </div>

    <div class="admin-actions">
        <h2>Quick Actions</h2>

        <div class="admin-actions-grid">
            <a href="<?php echo BASE_URL; ?>admin/add-product.php" class="admin-action-btn">
                Add Product
            </a>

            <a href="<?php echo BASE_URL; ?>admin/products.php" class="admin-action-btn">
                Manage Products
            </a>

            <a href="<?php echo BASE_URL; ?>pages/catalog.php" class="admin-action-btn">
                View Store
            </a>
        </div>
    </div>

    <div class="dashboard-sections-grid">

        <div class="dashboard-section-card">
            <h2>Low Stock Products</h2>

            <?php if ($lowStockProducts && mysqli_num_rows($lowStockProducts) > 0) { ?>
                <?php while ($product = mysqli_fetch_assoc($lowStockProducts)) { ?>
                    <div class="dashboard-list-item">
                        <div>
                            <h4><?php echo $product["name"]; ?></h4>
                            <span>Only <?php echo $product["stock_quantity"]; ?> left</span>
                        </div>

                        <a href="<?php echo BASE_URL; ?>admin/edit-product.php?id=<?php echo $product["id"]; ?>">
                            Edit
                        </a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="dashboard-empty-message">No low stock products.</p>
            <?php } ?>
        </div>

        <div class="dashboard-section-card">
            <h2>Out Of Stock Products</h2>

            <?php if ($outOfStockProducts && mysqli_num_rows($outOfStockProducts) > 0) { ?>
                <?php while ($product = mysqli_fetch_assoc($outOfStockProducts)) { ?>
                    <div class="dashboard-list-item">
                        <div>
                            <h4><?php echo $product["name"]; ?></h4>
                            <span class="danger-text">Out of stock</span>
                        </div>

                        <a href="<?php echo BASE_URL; ?>admin/edit-product.php?id=<?php echo $product["id"]; ?>">
                            Restock
                        </a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="dashboard-empty-message">No out of stock products.</p>
            <?php } ?>
        </div>

    </div>

    <div class="dashboard-section-card recent-orders-card">
        <h2>Recent Orders</h2>

        <?php if ($recentOrders && mysqli_num_rows($recentOrders) > 0) { ?>
            <div class="recent-orders-table-wrapper">
                <table class="recent-orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($recentOrders)) { ?>
                            <tr>
                                <td>#<?php echo $order["id"]; ?></td>
                                <td><?php echo $order["full_name"]; ?></td>
                                <td>₪<?php echo number_format($order["total_price"], 2); ?></td>
                                <td>
                                    <?php
                                    if (isset($order["created_at"])) {
                                        echo $order["created_at"];
                                    } else {
                                        echo "No date";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <p class="dashboard-empty-message">No recent orders yet.</p>
        <?php } ?>
    </div>

</section>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>