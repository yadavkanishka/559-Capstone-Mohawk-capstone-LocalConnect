<?php
require_once __DIR__ . '/config/config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    verify_csrf();
    $email = trim($_POST['email']);
    $userPassword = $_POST['password'];
    $full_name = trim($_POST['full_name']);
    $location = trim($_POST['location'] ?? '');

    // Check required fields
    if (empty($email) || empty($userPassword) || empty($full_name)) {
        $message = "All fields are required.";
    }

    // Validate email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    }

    // Validate password
    elseif (!isPasswordValid($userPassword)) {
        $message = "Password must be at least 8 characters and include uppercase, lowercase, a number, and a special character.";
    }

    else {
        try {
            $conn = getDBConnection();

            // Check if email already exists
            $checkStmt = $conn->prepare(
                "SELECT id FROM users WHERE email = :email"
            );

            $checkStmt->execute([
                ':email' => $email
            ]);

            if ($checkStmt->fetch()) {
                $message = "An account with this email already exists.";
            } else {

                // Hash password before storing it
                $passwordHash = password_hash($userPassword, PASSWORD_DEFAULT);
                // Insert new user
                $stmt = $conn->prepare(
                    "INSERT INTO users (email, password_hash, full_name, location)
                     VALUES (:email, :password_hash, :full_name, :location)"
                );

                $stmt->execute([
                    ':email' => $email,
                    ':password_hash' => $passwordHash,
                    ':full_name' => $full_name,
                    ':location' => $location
                ]);

                $user = ['id' => $conn->lastInsertId()];
                login_user($user);
                redirect('/posts.php');
            }

        } catch (PDOException $e) {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>




<?php
$page_title = 'Create Account';
require_once __DIR__ . '/includes/header.php';
?>

<main class="register-page">
    <section class="register-card">
        <h1>Create an Account</h1>
        <p class="register-subtitle">
            Join your local community and start collaborating.
        </p>

        <?php if (!empty($message)): ?>
            <p class="register-error" role="alert">
                <?= e($message) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
            <?= csrf_field() ?>

            <div class="register-form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    required
                >
            </div>

            <div class="register-form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    required
                >
                <small class="password-help">
                    At least 8 characters, including uppercase and lowercase
                    letters, a number, and a special character.
                </small>
            </div>

            <div class="register-form-group">
                <label for="full_name">Full Name</label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    autocomplete="name"
                    value="<?= e($_POST['full_name'] ?? '') ?>"
                    required
                >
            </div>

            <div class="register-form-group">
                <label for="location">
                    Location <span class="optional-label">(optional)</span>
                </label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    placeholder="e.g. Toronto, ON"
                    autocomplete="address-level2"
                    value="<?= e($_POST['location'] ?? '') ?>"
                >
            </div>

            <button type="submit" class="btn btn-primary register-submit">
                Create Account
            </button>
        </form>

        <p class="register-login">
            Already have an account?
            <a href="/559-Capstone-Mohawk-capstone-LocalConnect/login.php">
                Log in
            </a>
        </p>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

