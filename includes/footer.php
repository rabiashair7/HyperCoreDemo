<footer class="site-footer">

    <div class="footer-container">

        <div class="footer-brand">
            <h2><?php echo SITE_NAME; ?></h2>
            <p>
                Premium gaming and electronics store for players,
                creators, and tech enthusiasts.
            </p>
        </div>

        <div class="footer-links">
            <h3>Quick Links</h3>

            <a href="<?php echo BASE_URL; ?>index.php">Home</a>
            <a href="<?php echo BASE_URL; ?>pages/catalog.php">Store</a>
            <a href="<?php echo BASE_URL; ?>pages/cart.php">Cart</a>
            <a href="<?php echo BASE_URL; ?>pages/profile.php">Profile</a>
        </div>

        <div class="footer-contact">
            <h3>HyperCore</h3>
            <p>Gaming Hardware</p>
            <p>Electronics</p>
            <p>Digital Store Experience</p>
        </div>

    </div>

    <div class="footer-bottom">
        <p>
            &copy; <?php echo date("Y"); ?>
            <?php echo SITE_NAME; ?>.
            All Rights Reserved.
        </p>
    </div>

</footer>

<?php if (!isset($_COOKIE["cookie_consent"])) { ?>

<div id="cookie-banner" class="cookie-banner">

    <div class="cookie-content">

        <p>
            HyperCore uses cookies to improve your experience,
            remember recently viewed products, and enhance store functionality.
        </p>

        <button id="accept-cookies">
            Accept Cookies
        </button>

    </div>

</div>

<script>
document
    .getElementById("accept-cookies")
    .addEventListener("click", function () {

        document.cookie =
            "cookie_consent=accepted; path=/; max-age=" +
            (60 * 60 * 24 * 365);

        document.getElementById("cookie-banner").style.display = "none";

        location.reload();
    });
</script>

<?php } ?>

</body>
</html>