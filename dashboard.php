<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

require_login();

$user = current_user();

$page_title = 'Dashboard';
?>

<!DOCTYPE html>
<html>
<head>
    <title>LocalConnect Dashboard</title>
</head>

<body>

<h1>LocalConnect Dashboard</h1>

<?php if ($user): ?>
    <p>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</p>
    <p>You are successfully logged in.</p>
<?php endif; ?>

<a href="logout.php">Logout</a>

</body>
</html>