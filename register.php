<form method="POST">
    Email: <input name="email"><br>
    Password: <input name="password" type="password"><br>
    Full Name: <input name="full_name"><br>
    <button type="submit">Test Submit</button>
</form>

<?php
require_once 'config/db.php';
require_once 'includes/validation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);

    if (!isPasswordValid($password)) {
        die("Password must be at least 8 characters and include uppercase, lowercase, a number, and a special character.");
    }

    echo "Received: " . $email . " / " . $full_name;
}
?>

