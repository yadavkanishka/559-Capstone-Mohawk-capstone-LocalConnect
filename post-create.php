<?php

require_once __DIR__ . '/config/config.php';

require_login();

$user = current_user();

$errors = [];
$message = "";

$title = "";
$description = "";
$required_skills = "";
$location = $user['location'] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    verify_csrf();

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $required_skills = trim($_POST['required_skills'] ?? '');
    $location = trim($_POST['location'] ?? '');

    // Validate title
    if ($title === '') {
        $errors[] = "Title is required.";
    } elseif (strlen($title) > 200) {
        $errors[] = "Title must be 200 characters or less.";
    }

    // Validate description
    if ($description === '') {
        $errors[] = "Description is required.";
    }

    // Validate location
    if (strlen($location) > 100) {
        $errors[] = "Location must be 100 characters or less.";
    }

    // Validate skills
    if (strlen($required_skills) > 2000) {
        $errors[] = "Required skills must be 2000 characters or less.";
    }

    if (empty($errors)) {

        try {

            $pdo = getDBConnection();

            $stmt = $pdo->prepare(
                "INSERT INTO posts
                (user_id, title, description, required_skills, location)
                VALUES
                (:user_id, :title, :description, :required_skills, :location)"
            );

            $stmt->execute([
                ':user_id' => $user['id'],
                ':title' => $title,
                ':description' => $description,
                ':required_skills' => $required_skills,
                ':location' => $location
            ]);

            redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');

        } catch (PDOException $e) {

            $errors[] = "Unable to create the collaboration post. Please try again.";

        }
    }
}

$page_title = 'Create Collaboration';

require __DIR__ . '/includes/header.php';
?>

<div class="page-head">
    <div class="container">
        <h1>Create a Collaboration</h1>
        <p class="muted">
            Create a post and connect with people in your local community.
        </p>
    </div>
</div>

<div class="container">

    <?php if (!empty($errors)): ?>

        <div class="card card-pad mb-3">

            <?php foreach ($errors as $error): ?>

                <p><?= e($error) ?></p>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <div class="card card-pad">

        <form method="POST">

            <?= csrf_field() ?>

            <div class="form-group">

                <label for="title">
                    Collaboration Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= e($title) ?>"
                    maxlength="200"
                    placeholder="e.g. Build a community website"
                    required
                >

            </div>

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    placeholder="Describe your collaboration or project..."
                    required
                ><?= e($description) ?></textarea>

            </div>

            <div class="form-group">

                <label for="required_skills">
                    Required Skills
                </label>

                <textarea
                    id="required_skills"
                    name="required_skills"
                    placeholder="e.g. PHP, JavaScript, UI/UX"
                ><?= e($required_skills) ?></textarea>

            </div>

            <div class="form-group">

                <label for="location">
                    Location
                </label>

                <input
                    type="text"
                    id="location"
                    name="location"
                    value="<?= e($location) ?>"
                    maxlength="100"
                    placeholder="e.g. Hamilton, ON"
                >

            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Collaboration
            </button>

            <a
                href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php"
                class="btn"
            >
                Cancel
            </a>

        </form>

    </div>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>