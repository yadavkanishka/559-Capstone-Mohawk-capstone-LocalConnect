<?php
require_once __DIR__ . '/config/config.php';

require_login();

$user = current_user();
$message = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    verify_csrf();

    $full_name = trim($_POST['full_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $interests = trim($_POST['interests'] ?? '');
    $biography = trim($_POST['biography'] ?? '');

    // Validate full name
    if ($full_name === '') {
        $errors[] = "Full name is required.";
    } elseif (strlen($full_name) > 150) {
        $errors[] = "Full name must be 150 characters or less.";
    }

    // Validate location
    if (strlen($location) > 100) {
        $errors[] = "Location must be 100 characters or less.";
    }

    // Validate profile fields
    if (strlen($skills) > 2000) {
        $errors[] = "Skills must be 2000 characters or less.";
    }

    if (strlen($interests) > 2000) {
        $errors[] = "Interests must be 2000 characters or less.";
    }

    if (strlen($biography) > 5000) {
        $errors[] = "Biography must be 5000 characters or less.";
    }

    if (empty($errors)) {
        try {
            $pdo = getDBConnection();

            $stmt = $pdo->prepare(
                "UPDATE users
                 SET full_name = :full_name,
                     location = :location,
                     skills = :skills,
                     interests = :interests,
                     biography = :biography
                 WHERE id = :id"
            );

            $stmt->execute([
                ':full_name' => $full_name,
                ':location' => $location,
                ':skills' => $skills,
                ':interests' => $interests,
                ':biography' => $biography,
                ':id' => $user['id']
            ]);

            $message = "Profile updated successfully.";

            // Reload the updated user from the database
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $user = $stmt->fetch();

        } catch (PDOException $e) {
            $errors[] = "Unable to update your profile. Please try again.";
        }
    }
}

$page_title = 'My Profile';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> - LocalConnect</title>
</head>

<body>

<h1>My Profile</h1>

<?php if (!empty($message)): ?>
    <p><?= e($message) ?></p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div>
        <?php foreach ($errors as $error): ?>
            <p><?= e($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<form method="POST">

    <?= csrf_field() ?>

    <p>
        <strong>Email:</strong>
        <?= e($user['email']) ?>
    </p>

    <label for="full_name">Full Name:</label>
    <input
        type="text"
        id="full_name"
        name="full_name"
        value="<?= e($user['full_name']) ?>"
        maxlength="150"
        required
    >

    <br><br>

    <label for="location">Location:</label>
    <input
        type="text"
        id="location"
        name="location"
        value="<?= e($user['location']) ?>"
        maxlength="100"
        placeholder="e.g. Hamilton, ON"
    >

    <br><br>

    <label for="skills">Skills:</label>
    <textarea
        id="skills"
        name="skills"
        rows="4"
        maxlength="2000"
        placeholder="e.g. PHP, JavaScript, SQL"
    ><?= e($user['skills']) ?></textarea>

    <br><br>

    <label for="interests">Interests:</label>
    <textarea
        id="interests"
        name="interests"
        rows="4"
        maxlength="2000"
        placeholder="e.g. Technology, volunteering, sports"
    ><?= e($user['interests']) ?></textarea>

    <br><br>

    <label for="biography">Biography:</label>
    <textarea
        id="biography"
        name="biography"
        rows="6"
        maxlength="5000"
        placeholder="Tell other LocalConnect users a little about yourself..."
    ><?= e($user['biography']) ?></textarea>

    <br><br>

    <button type="submit">Save Profile</button>

</form>

<p>
    <a href="index.php">Back to Home</a>
</p>

</body>
</html>