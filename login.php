<?php
session_start();

require_once 'config/db.php';

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>LocalConnect Login</title>
</head>

<body>

<h1>Login</h1>

<form method="POST">

    <label>Email:</label>
    <input type="email" name="email" required>
    <br><br>

    <label>Password:</label>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit">Login</button>

</form>
<?php if (!empty($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
</body>
</html>