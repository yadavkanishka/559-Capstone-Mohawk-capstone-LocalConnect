<?php
require_once __DIR__ . '/config/config.php';

$pdo = getDBConnection();

$recent = $pdo->query(
    "SELECT p.*, u.full_name
     FROM posts p
     JOIN users u ON u.id = p.user_id
     ORDER BY p.created_at DESC
     LIMIT 3"
)->fetchAll();

$userCount = (int)$pdo->query(
    "SELECT COUNT(*) FROM users"
)->fetchColumn();

$postCount = (int)$pdo->query(
    "SELECT COUNT(*) FROM posts"
)->fetchColumn();

$page_title = 'Find your people, locally';
?>


<?php require __DIR__ . '/includes/header.php'; ?>

<section class="hero">
    <div class="container hero-grid">

        <div>

            <span class="eyebrow">Local Collaboration Network</span>

            <h1>Find the right people to build something together.</h1>

            <p class="lead mt-2">
                LocalConnect helps you discover collaborators nearby &mdash;
                share what you're building, list the skills you need,
                and connect with people in your community.
            </p>

            <div class="flex gap-1 wrap mt-3">

                <?php if (is_logged_in()): ?>

                    <a
                        href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php"
                        class="btn btn-primary"
                        data-testid="hero-browse-button"
                    >Browse posts</a>

                    <a
                        href="/559-Capstone-Mohawk-capstone-LocalConnect/post-create.php"
                        class="btn btn-outline"
                        data-testid="hero-create-button"
                    >Post a collaboration</a>

                <?php else: ?>

                    <a
                        href="/559-Capstone-Mohawk-capstone-LocalConnect/register.php"
                        class="btn btn-primary"
                        data-testid="hero-register-button"
                    >Get started &mdash; it's free</a>

                    <a
                        href="/559-Capstone-Mohawk-capstone-LocalConnect/posts.php"
                        class="btn btn-outline"
                        data-testid="hero-browse-button"
                    >Browse posts</a>

                <?php endif; ?>

            </div>

        </div>
        <div class="hero-img">
    <img
        src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1000&q=85"
        alt="People collaborating around a table"
    >
</div>            
    </div>
</section>

<section class="pad">
    <div class="container">

        <div class="stats">

            <div class="stat">
                <div class="n"><?= $userCount ?></div>
                <div class="l">Members</div>
            </div>

            <div class="stat">
                <div class="n"><?= $postCount ?></div>
                <div class="l">Open collaborations</div>
            </div>

            <div class="stat">
                <div class="n">Local</div>
                <div class="l">Community-first</div>
            </div>

        </div>

    </div>
</section>

<section class="pad">
    <div class="container">

        <div class="features">

            <div class="card card-pad">
                <div class="feature-icon">&#128269;</div>

                <h3>Discover collaborations</h3>

                <p class="muted">
                    Search and filter posts by location and the skills needed
                    to find projects that fit you.
                </p>
            </div>

            <div class="card card-pad">
                <div class="feature-icon">&#129309;</div>

                <h3>Send requests</h3>

                <p class="muted">
                    Reach out with a personal note. Accept or decline requests
                    from your inbox.
                </p>
            </div>

            <div class="card card-pad">
                <div class="feature-icon">&#127968;</div>

                <h3>Stay local</h3>

                <p class="muted">
                    Connect with people in your area for meaningful,
                    in-person collaboration.
                </p>
            </div>

        </div>

    </div>
</section>

<?php if ($recent): ?>

<section class="pad">
    <div class="container">

        <div class="home-section-heading">

            <h2>Latest collaborations</h2>

            <a
                href="/posts.php"
                class="btn btn-ghost btn-sm"
            >View all &rarr;</a>

        </div>

        <div class="feed-grid">

            <?php foreach ($recent as $p): ?>

                <?php require __DIR__ . '/includes/post_card.php'; ?>

            <?php endforeach; ?>

        </div>

    </div>
</section>

<?php endif; ?>