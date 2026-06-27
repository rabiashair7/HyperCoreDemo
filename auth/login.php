<?php
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/top-navbar.php";
?>

<section class="auth-page">

    <form class="auth-form"
          action="<?php echo BASE_URL; ?>auth/process/login-process.php"
          method="POST">

        <h1>Welcome Back</h1>

        <p class="auth-subtitle">
            Sign in to continue your HyperCore journey
        </p>

        <input type="email"
               name="email"
               placeholder="Email"
               required>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <button type="submit">
            Login
        </button>

        <p>
            Don't have an account?
            <a href="<?php echo BASE_URL; ?>auth/register.php">
                Register
            </a>
        </p>

    </form>

</section>

<?php
require_once __DIR__ . "/../includes/footer.php";
?>