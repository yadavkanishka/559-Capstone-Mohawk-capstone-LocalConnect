<?php

require_once __DIR__ . '/config/config.php';

require_login();

$pdo = getDBConnection();

$post_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$post_id) {
    flash('error', 'Invalid post.');
    redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');
}

/*
 * Get the post
 */
$stmt = $pdo->prepare("
    SELECT *
    FROM posts
    WHERE id = ?
");
$stmt->execute([$post_id]);

$post = $stmt->fetch();

if (!$post) {
    flash('error', 'Post not found.');
    redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');
}

/*
 * Only the owner can edit their post
 */
if ((int)$post['user_id'] !== (int)current_user()['id']) {
    flash('error', 'You can only edit your own posts.');
    redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');
}

/*
 * Handle form submission
 */
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verify_csrf();

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $required_skills = trim($_POST['required_skills'] ?? '');
    $location = trim($_POST['location'] ?? '');

    /*
     * Validation
     */
    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (strlen($title) > 200) {
        $errors[] = 'Title must be 200 characters or less.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (strlen($required_skills) > 2000) {
        $errors[] = 'Required skills must be 2000 characters or less.';
    }

    if (strlen($location) > 100) {
        $errors[] = 'Location must be 100 characters or less.';
    }

    /*
     * Update post
     */
    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE posts
            SET
                title = ?,
                description = ?,
                required_skills = ?,
                location = ?
            WHERE id = ?
              AND user_id = ?
        ");

        $stmt->execute([
            $title,
            $description,
            $required_skills,
            $location,
            $post_id,
            current_user()['id']
        ]);

        flash('success', 'Post updated successfully.');

        redirect('/559-Capstone-Mohawk-capstone-LocalConnect/posts.php');
    }

    /*
     * Keep entered values if validation fails
     */
    $post['title'] = $title;
    $post['description'] = $description;
    $post['required_skills'] = $required_skills;
    $post['location'] = $location;
}

$page_title = 'Edit Collaboration Post';

require_once __DIR__ . '/includes/header.php';

?>

<div class="container">

    <div class="page-head">
        <h1>Edit Collaboration Post</h1>
        <p class="muted">
            Update your collaboration post information.
        </p>
    </div>

    <?php if (!empty($errors)): ?>

        <div class="card card-pad mb-3">

            <?php foreach ($errors as $error): ?>

                <p class="error">
                    <?= e($error) ?>
                </p>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <div class="card card-pad">

        <form method="POST">

            <?= csrf_field() ?>

            <div class="form-group">

                <label for="title">
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    maxlength="200"
                    value="<?= e($post['title']) ?>"
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
                    required
                ><?= e($post['description']) ?></textarea>

            </div>

            <div class="form-group">

                <label for="required_skills">
                    Required Skills
                </label>

                <textarea
                    id="required_skills"
                    name="required_skills"
                    maxlength="2000"
                ><?= e($post['required_skills']) ?></textarea>

            </div>

            <div class="form-group">

                <label for="location">
                    Location
                </label>

                <input
                    type="text"
                    id="location"
                    name="location"
                    maxlength="100"
                    value="<?= e($post['location']) ?>"
                >

            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Changes
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>