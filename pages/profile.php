<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";

if(!isset($_SESSION["user_id"])){
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}
?>

<section class="profile-page">

    <div class="profile-header">
        <h1>Welcome, <?php echo $_SESSION["full_name"]; ?></h1>
        <p>Manage your HyperCore account and shopping activity.</p>
    </div>

    <div class="profile-grid">

        <div class="profile-card account-card">
            <h2>Account Info</h2>

            <p><strong>Name:</strong> <?php echo $_SESSION["full_name"]; ?></p>
            <p><strong>Email:</strong> <?php echo $_SESSION["email"]; ?></p>
            <p><strong>Role:</strong> <?php echo $_SESSION["role"]; ?></p>
        </div>

        <a href="<?php echo BASE_URL; ?>pages/cart.php" class="profile-card profile-link-card">
            <h2>Cart</h2>
            <p>View products you want to buy.</p>
        </a>

        

       

    </div>

    <div class="profile-actions">
        <a href="<?php echo BASE_URL; ?>auth/logout.php" class="logout-btn">
            Logout
        </a>
    </div>

</section>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>