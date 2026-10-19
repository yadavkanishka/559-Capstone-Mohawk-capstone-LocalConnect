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

<?php
require_once __DIR__ . '/includes/header.php';
?>

<main class="profile-page">
    <section class="profile-card">
        <header class="profile-header">
            <p class="profile-eyebrow">YOUR LOCALCONNECT SPACE</p>
            <h1>My Profile</h1>
            <p>
                Update your information so other LocalConnect users
                can learn more about you.
            </p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="profile-message" role="status">
                <?= e($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="profile-errors" role="alert">
                <?php foreach ($errors as $error): ?>
                    <p><?= e($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div id="profile-view">
            <div class="profile-details">
                <div class="profile-detail">
                    <span>Email address</span>
                    <p><?= e($user['email'] ?? '') ?></p>
                </div>

                <div class="profile-detail">
                    <span>Full name</span>
                    <p><?= e($user['full_name'] ?? '') ?: 'Not provided' ?></p>
                </div>

                <div class="profile-detail">
                    <span>Location</span>
                    <p><?= e($user['location'] ?? '') ?: 'Not provided' ?></p>
                </div>

                <div class="profile-detail">
                    <span>Skills</span>
                    <p><?= !empty($user['skills']) ? nl2br(e($user['skills'])) : 'Not provided' ?></p>
                </div>

                <div class="profile-detail">
                    <span>Interests</span>
                    <p><?= !empty($user['interests']) ? nl2br(e($user['interests'])) : 'Not provided' ?></p>
                </div>

                <div class="profile-detail">
                    <span>Biography</span>
                    <p><?= !empty($user['biography']) ? nl2br(e($user['biography'])) : 'Not provided' ?></p>
                </div>
            </div>

            <button type="button" class="btn btn-primary" onclick="showEditForm()">
                Edit Profile
            </button>
        </div>

        <div id="profile-edit" hidden>
            <form method="POST">
                <?= csrf_field() ?>

                <div class="profile-detail profile-email">
                    <span>Email address</span>
                    <p><?= e($user['email'] ?? '') ?></p>
                    <small>Email cannot be changed here.</small>
                </div>

                <div class="profile-form-group">
                    <label for="full_name">Full name</label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        value="<?= e($user['full_name'] ?? '') ?>"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="profile-form-group">
                    <label for="location">Location</label>
                    <input
                        type="text"
                        id="location"
                        name="location"
                        value="<?= e($user['location'] ?? '') ?>"
                        maxlength="100"
                        placeholder="e.g. Hamilton, ON"
                    >
                </div>

                <div class="profile-form-group">
                    <label for="skills">Skills</label>
                    <textarea
                        id="skills"
                        name="skills"
                        rows="4"
                        maxlength="2000"
                        placeholder="e.g. PHP, JavaScript, SQL"
                    ><?= e($user['skills'] ?? '') ?></textarea>
                </div>

                <div class="profile-form-group">
                    <label for="interests">Interests</label>
                    <textarea
                        id="interests"
                        name="interests"
                        rows="4"
                        maxlength="2000"
                        placeholder="e.g. Technology, volunteering, sports"
                    ><?= e($user['interests'] ?? '') ?></textarea>
                </div>

                <div class="profile-form-group">
                    <label for="biography">Biography</label>
                    <textarea
                        id="biography"
                        name="biography"
                        rows="6"
                        maxlength="5000"
                        placeholder="Tell other LocalConnect users about yourself..."
                    ><?= e($user['biography'] ?? '') ?></textarea>
                </div>

                <div class="profile-button-row">
                    <button type="submit" class="btn btn-primary">
                        Save Profile
                    </button>

                    <button type="button" class="btn" onclick="cancelEdit()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<script>
function showEditForm() {
    document.getElementById('profile-view').hidden = true;
    document.getElementById('profile-edit').hidden = false;
}

function cancelEdit() {
    document.getElementById('profile-edit').hidden = true;
    document.getElementById('profile-view').hidden = false;
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>



