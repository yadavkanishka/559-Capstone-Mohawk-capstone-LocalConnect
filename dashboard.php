
<?php
require_once __DIR__ . '/config/config.php';

require_login();

$user = current_user();
$conn = getDBConnection();

// Count the logged-in user's posts.
$countStmt = $conn->prepare(
    "SELECT COUNT(*) FROM posts WHERE user_id = :user_id"
);
$countStmt->execute([':user_id' => $user['id']]);
$totalPosts = (int) $countStmt->fetchColumn();

// Load the user's recent posts.
$postStmt = $conn->prepare(
    "SELECT id, title, description, location, created_at
     FROM posts
     WHERE user_id = :user_id
     ORDER BY created_at DESC
     LIMIT 5"
);
$postStmt->execute([':user_id' => $user['id']]);
$myPosts = $postStmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
?>

<main class="dashboard-page">
    <section class="dashboard-welcome">
        <p class="dashboard-eyebrow">YOUR LOCALCONNECT SPACE</p>

        <h1>
            Welcome back,
            <?= e($user['full_name'] ?? 'Member') ?>!
        </h1>

        <p>Your next collaboration starts here.</p>

        <div class="dashboard-quick-actions">
            <a
                class="btn btn-primary"
                href="/559-Capstone-Mohawk-capstone-LocalConnect/post-create.php"
            >
                Create a Post
            </a>

            <a
                class="btn"
                href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php"
            >
                Browse Collaborations
            </a>

            <a
                class="btn"
                href="/559-Capstone-Mohawk-capstone-LocalConnect/profile.php"
            >
                My Profile
            </a>
        </div>
    </section>

    <section class="dashboard-summary">
        <article class="dashboard-summary-card">
            <h2>Your Posts</h2>
            <p class="dashboard-number"><?= $totalPosts ?></p>
            <a href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php">
                Browse collaborations
            </a>
        </article>

        <article class="dashboard-summary-card">
            <h2>Your Profile</h2>
            <p>Keep your skills and interests up to date.</p>
            <a href="/559-Capstone-Mohawk-capstone-LocalConnect/profile.php">
                View profile
            </a>
        </article>
    </section>

    <section class="dashboard-posts">
        <h2>Your Recent Collaborations</h2>

        <?php if (empty($myPosts)): ?>
            <div class="dashboard-empty">
                <p>You haven't created any collaboration posts yet.</p>
                <a
                    class="btn btn-primary"
                    href="/559-Capstone-Mohawk-capstone-LocalConnect/post-create.php"
                >
                    Create your first post
                </a>
            </div>
        <?php else: ?>
            <div class="dashboard-post-list">
                <?php foreach ($myPosts as $post): ?>
                    <article class="dashboard-post-card">
                        <h3><?= e($post['title']) ?></h3>

                        <p><?= e($post['description']) ?></p>

                        <?php if (!empty($post['location'])): ?>
                            <p class="dashboard-post-location">
                                <?= e($post['location']) ?>
                            </p>
                        <?php endif; ?>

                        <a
                            href="/559-Capstone-Mohawk-capstone-LocalConnect/post-edit.php?id=<?= (int) $post['id'] ?>"
                        >
                            Manage post
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
