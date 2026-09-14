

<?php
require_once 'config/db.php';
require_once 'includes/validation.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $full_name = trim($_POST['full_name']);

    echo "Received: " . $email . " / " . $full_name;
}
?>

