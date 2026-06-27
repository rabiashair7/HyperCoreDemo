<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<nav class="top-navbar">

    <div class="nav-logo">
        <a href="<?php echo BASE_URL; ?>index.php">
            <?php echo SITE_NAME; ?>
        </a>
    </div>

    <form class="navbar-search" action="<?php echo BASE_URL; ?>pages/search-results.php" method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search products..."
            required
        >

        <button type="submit">
            Search
        </button>
    </form>

    <div class="nav-links">

        <a href="<?php echo BASE_URL; ?>pages/catalog.php">
            Store
        </a>

        <a href="<?php echo BASE_URL; ?>pages/cart.php">
            Cart
        </a>

        <?php if(isset($_SESSION['user_id'])) { ?>

            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>

                <a href="<?php echo BASE_URL; ?>admin/dashboard.php">
                    Admin Dashboard
                </a>

            <?php } else { ?>

                <a href="<?php echo BASE_URL; ?>pages/profile.php">
                    Profile
                </a>

            <?php } ?>

            <a href="<?php echo BASE_URL; ?>auth/logout.php">
                Logout
            </a>

        <?php } else { ?>

            <a href="<?php echo BASE_URL; ?>auth/login.php">
                Login
            </a>

            <a href="<?php echo BASE_URL; ?>auth/register.php">
                Register
            </a>

        <?php } ?>

    </div>

</nav>