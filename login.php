<?php
require_once __DIR__ . '/config/config.php';

$message = "";

// Default destination after successful login.
$redirect = $_GET['redirect'] ?? $_POST['redirect'] ?? '/dashboard.php';

// Allow only safe internal redirects.
if (
    !is_string($redirect) ||
    $redirect === '' ||
    $redirect[0] !== '/' ||
    str_starts_with($redirect, '//') ||
    parse_url($redirect, PHP_URL_HOST) !== null
) {
    $redirect = '/dashboard.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $email = trim($_POST['email'] ?? '');
    $userPassword = $_POST['password'] ?? '';

    if ($email === '' || $userPassword === '') {
        $message = "Email and password are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";

    } else {
        try {
            $conn = getDBConnection();

            $stmt = $conn->prepare(
                "SELECT id, email, password_hash, full_name, role
                 FROM users
                 WHERE email = ?"
            );

            $stmt->execute([$email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($userPassword, $user['password_hash'])) {

                login_user($user);

                redirect($redirect);

            } else {
                $message = "Invalid email or password.";
            }

        } catch (Throwable $e) {
            error_log("Login error: " . $e->getMessage());
            $message = "Login failed. Please try again.";
        }
    }
}

$page_title = 'Login';
require_once __DIR__ . '/includes/header.php';
?>


<main class="login-page">
    <div class="login-card">

        <h1>Welcome Back</h1>

        <p class="login-subtitle">
            Log in to connect with your local community.
        </p>

        <?php if (!empty($message)): ?>
            <p class="form-error" role="alert">
                <?= e($message) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
             <?= csrf_field() ?>

            <input
                type="hidden"
                name="redirect"
                value="<?= e($redirect) ?>"
            >
            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Login
            </button>
        </form>

        <p class="login-signup">
            Don't have an account?
            <a href="/register.php">
                Create an account
            </a>
        </p>

    </div>
</main>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
