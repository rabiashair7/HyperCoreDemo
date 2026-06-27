<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";
?>

<section class="auth-page">

    <form id="registerForm"
          class="auth-form"
          action="<?php echo BASE_URL; ?>auth/process/register-process.php"
          method="POST">

        <h1>Join HyperCore</h1>

        <p class="auth-subtitle">
            Create your gaming account
        </p>

        <input type="text"
               id="full_name"
               name="full_name"
               placeholder="Full Name"
               required>

        <input type="email"
               id="email"
               name="email"
               placeholder="Email"
               required>

        <input type="password"
               id="password"
               name="password"
               placeholder="Password"
               required>

        <input type="password"
               id="confirm_password"
               name="confirm_password"
               placeholder="Confirm Password"
               required>

        <button type="submit">
            Register
        </button>

        <p>
            Already have an account?
            <a href="<?php echo BASE_URL; ?>auth/login.php">
                Login
            </a>
        </p>

    </form>

</section>

<script src="<?php echo BASE_URL; ?>assets/js/classes/User.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/register.js"></script>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>