<?php
require_once __DIR__ . '/config/config.php';
if (is_logged_in()) redirect('/posts.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $userPassword = $_POST['password'];
    $full_name = trim($_POST['full_name']);

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
                    "INSERT INTO users (email, password_hash, full_name)
                     VALUES (:email, :password_hash, :full_name)"
                );

                $stmt->execute([
                    ':email' => $email,
                    ':password_hash' => $passwordHash,
                    ':full_name' => $full_name
                ]);

                $message = "Registration successful!";
            }

        } catch (PDOException $e) {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LocalConnect Registration</title>
</head>

<body>

<h1>Create an Account</h1>

<form method="POST">

    <label>Email:</label>
    <input type="email" name="email" required>
    <br><br>

    <label>Password:</label>
    <input type="password" name="password" required>
    <br><br>

    <label>Full Name:</label>
    <input type="text" name="full_name" required>
    <br><br>

    <button type="submit">Register</button>

</form>

<?php if (!empty($message)): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

</body>
</html>