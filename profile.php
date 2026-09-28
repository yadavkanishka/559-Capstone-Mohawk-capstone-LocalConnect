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

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #222;
        }

        .navbar {
            background: #1f2937;
            color: white;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .profile-header {
            margin-bottom: 25px;
        }

        .profile-header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .profile-header p {
            margin: 0;
            color: #666;
        }

        .message {
            background: #e8f7ed;
            color: #176b36;
            border: 1px solid #b7e4c7;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .errors {
            background: #fdecec;
            color: #9b1c1c;
            border: 1px solid #f5b5b5;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .errors p {
            margin: 4px 0;
        }

        .email-box {
            background: #f3f4f6;
            border: 1px solid #ddd;
            padding: 14px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .email-box strong {
            display: block;
            margin-bottom: 5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        input,
        textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            font-family: inherit;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        textarea {
            resize: vertical;
        }

        .help-text {
            display: block;
            margin-top: 5px;
            font-size: 13px;
            color: #777;
        }

        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .back-link {
            display: inline-block;
            padding: 12px 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            background: white;
        }

        .back-link:hover {
            background: #f3f4f6;
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 15px 20px;
            }

            .navbar a {
                margin-left: 10px;
            }

            .container {
                margin: 20px auto;
            }

            .profile-card {
                padding: 20px;
            }

            .button-row {
                flex-direction: column;
            }

            button,
            .back-link {
                text-align: center;
                width: 100%;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <h2>LocalConnect</h2>

    <div>
        <a href="index.php">Home</a>
        <a href="posts.php">Posts</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<main class="container">

    <div class="profile-card">

        <div class="profile-header">
            <h1>My Profile</h1>
            <p>Update your information so other LocalConnect users can learn more about you.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="message">
                <?= e($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= e($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="profile-view">

    <p>
        <strong>Email</strong><br>
        <?= e($user['email']) ?>
    </p>

    <p>
        <strong>Full Name</strong><br>
        <?= e($user['full_name']) ?: 'Not provided' ?>
    </p>

    <p>
        <strong>Location</strong><br>
        <?= e($user['location']) ?: 'Not provided' ?>
    </p>

    <p>
        <strong>Skills</strong><br>
        <?= nl2br(e($user['skills'])) ?: 'Not provided' ?>
    </p>

    <p>
        <strong>Interests</strong><br>
        <?= nl2br(e($user['interests'])) ?: 'Not provided' ?>
    </p>

    <p>
        <strong>Biography</strong><br>
        <?= nl2br(e($user['biography'])) ?: 'Not provided' ?>
    </p>

    <button type="button" onclick="showEditForm()">
        Edit Profile
    </button>

</div>


<div id="profile-edit" style="display: none;">

    <form method="POST">

        <?= csrf_field() ?>

        <p>
            <strong>Email:</strong>
            <?= e($user['email']) ?>
        </p>

        <label for="full_name">Full Name:</label><br>
        <input
            type="text"
            id="full_name"
            name="full_name"
            value="<?= e($user['full_name']) ?>"
            maxlength="150"
            required
        >

        <br><br>

        <label for="location">Location:</label><br>
        <input
            type="text"
            id="location"
            name="location"
            value="<?= e($user['location']) ?>"
            maxlength="100"
            placeholder="e.g. Hamilton, ON"
        >

        <br><br>

        <label for="skills">Skills:</label><br>
        <textarea
            id="skills"
            name="skills"
            rows="4"
            maxlength="2000"
            placeholder="e.g. PHP, JavaScript, SQL"
        ><?= e($user['skills']) ?></textarea>

        <br><br>

        <label for="interests">Interests:</label><br>
        <textarea
            id="interests"
            name="interests"
            rows="4"
            maxlength="2000"
            placeholder="e.g. Technology, volunteering, sports"
        ><?= e($user['interests']) ?></textarea>

        <br><br>

        <label for="biography">Biography:</label><br>
        <textarea
            id="biography"
            name="biography"
            rows="6"
            maxlength="5000"
            placeholder="Tell other LocalConnect users a little about yourself..."
        ><?= e($user['biography']) ?></textarea>

        <br><br>

        <button type="submit">Save Profile</button>

        <button type="button" onclick="cancelEdit()">
            Cancel
        </button>

    </form>

</div>    </div>

</main>
<script>
function showEditForm() {
    document.getElementById('profile-view').style.display = 'none';
    document.getElementById('profile-edit').style.display = 'block';
}

function cancelEdit() {
    document.getElementById('profile-edit').style.display = 'none';
    document.getElementById('profile-view').style.display = 'block';
}
</script>

</body>
</html>