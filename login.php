<?php
session_start();

require_once 'config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $userPassword = $_POST['password'];

    // Check required fields
    if (empty($email) || empty($userPassword)) {
        $message = "Email and password are required.";
    }

    // Validate email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    }

    else {
        try {
            $conn = getDBConnection();

            // Find user by email
            $stmt = $conn->prepare(
                "SELECT id, email, password_hash, full_name, role
                 FROM users
                 WHERE email = :email"
            );

            $stmt->execute([
                ':email' => $email
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check password
            if ($user && password_verify($userPassword, $user['password_hash'])) {

                // Store login information in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];

                header('Location: dashboard.php');
                exit;
            } else {
                $message = "Invalid email or password.";
            }

        } catch (PDOException $e) {
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
            <a href="/559-Capstone-Mohawk-capstone-LocalConnect/register.php">
                Create an account
            </a>
        </p>

    </div>
</main>


<?php require_once __DIR__ . '/includes/footer.php'; ?>
