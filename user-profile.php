
<?php
require_once __DIR__ . '/config/config.php';

$conn = getDBConnection();

// Validate the profile ID from the URL.
$profileId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$profileId || $profileId < 1) {
    http_response_code(404);
    $profile = null;
    $profilePosts = [];
} else {
    // Retrieve only information intended to be shown publicly.
    $profileStmt = $conn->prepare(
        "SELECT id, full_name, location, skills, interests, biography, created_at
         FROM users
         WHERE id = :id"
    );

    $profileStmt->execute([':id' => $profileId]);
    $profile = $profileStmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        http_response_code(404);
        $profilePosts = [];
    } else {
        // Retrieve the user's collaboration posts.
        $postsStmt = $conn->prepare(
            "SELECT id, title, description, location, required_skills, created_at
             FROM posts
             WHERE user_id = :user_id
             ORDER BY created_at DESC"
        );

        $postsStmt->execute([':user_id' => $profileId]);
        $profilePosts = $postsStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$page_title = $profile
    ? (($profile['full_name'] ?: 'Member') . "'s Profile")
    : 'Profile Not Found';

require_once __DIR__ . '/includes/header.php';
?>

<main class="public-profile-page">
    <?php if (!$profile): ?>
        <section class="public-profile-card public-profile-not-found">
            <h1>Profile not found</h1>
            <p>This profile may not exist or the link may be invalid.</p>
            <a class="btn btn-primary"
               href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php">
                Browse Collaborations
            </a>
        </section>
    <?php else: ?>
        <section class="public-profile-card">
            <div class="public-profile-header">
                <div class="public-profile-avatar" aria-hidden="true">
                    <?= e(initials($profile['full_name'] ?? 'Member')) ?>
                </div>

                <div class="public-profile-heading">
                    <p class="public-profile-eyebrow">LOCALCONNECT MEMBER</p>
                    <h1><?= e($profile['full_name'] ?: 'Member') ?></h1>

                    <?php if (!empty($profile['location'])): ?>
                        <p class="public-profile-location">
                            <span aria-hidden="true">⌖</span>
                            <?= e($profile['location']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="public-profile-section">
                <h2>About</h2>

                <?php if (!empty($profile['biography'])): ?>
                    <p class="public-profile-biography"><?= nl2br(e($profile['biography'])) ?></p>
                <?php else: ?>
                    <p class="public-profile-muted">
                        This member hasn't added a biography yet.
                    </p>
                <?php endif; ?>
            </div>

            <div class="public-profile-section">
                <h2>Skills</h2>

                <?php if (!empty($profile['skills'])): ?>
                    <div class="public-profile-tags">
                        <?php foreach (skills_to_array($profile['skills']) as $skill): ?>
                            <span class="public-profile-tag"><?= e($skill) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="public-profile-muted">No skills added yet.</p>
                <?php endif; ?>
            </div>

            <div class="public-profile-section">
                <h2>Interests</h2>

                <?php if (!empty($profile['interests'])): ?>
                    <p class="public-profile-text"><?= nl2br(e($profile['interests'])) ?></p>
                <?php else: ?>
                    <p class="public-profile-muted">No interests added yet.</p>
                <?php endif; ?>
            </div>

            <div class="public-profile-section">
                <div class="public-profile-posts-heading">
                    <h2>Collaboration Posts</h2>
                    <span class="public-profile-post-count">
                        <?= count($profilePosts) ?>
                    </span>
                </div>

                <?php if (empty($profilePosts)): ?>
                    <p class="public-profile-muted">
                        This member hasn't created any collaboration posts yet.
                    </p>
                <?php else: ?>
                    <div class="public-profile-post-list">
                        <?php foreach ($profilePosts as $post): ?>
                            <article class="public-profile-post">
                                <h3><?= e($post['title']) ?></h3>
                                <p><?= nl2br(e($post['description'])) ?></p>

                                <?php if (!empty($post['location'])): ?>
                                    <p class="public-profile-post-location">
                                        <span aria-hidden="true">⌖</span>
                                        <?= e($post['location']) ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($post['required_skills'])): ?>
                                    <div class="public-profile-tags">
                                        <?php foreach (skills_to_array($post['required_skills']) as $skill): ?>
                                            <span class="public-profile-tag">
                                                <?= e($skill) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <p class="public-profile-post-date">
                                    <?= e(format_date($post['created_at'])) ?>
                                </p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="public-profile-actions">
                <a class="btn"
                   href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php">
                    Back to Collaborations
                </a>

                <?php if (is_logged_in() && (int) current_user()['id'] === (int) $profile['id']): ?>
                    <a class="btn btn-primary"
                       href="/559-Capstone-Mohawk-capstone-LocalConnect/profile.php">
                        Edit My Profile
                    </a>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
