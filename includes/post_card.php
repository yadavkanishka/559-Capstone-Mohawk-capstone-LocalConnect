
<div class="card post-card">

    <div class="post-card-top">
        <span class="post-category">COMMUNITY COLLABORATION</span>
        <span class="post-date">
            <?= e(format_date($p['created_at'])) ?>
        </span>
    </div>

    <div class="post-card-content">
        <h3 class="post-title"><?= e($p['title']) ?></h3>

        <p class="post-description">
            <?= e($p['description']) ?>
        </p>

        <?php if (!empty($p['required_skills'])): ?>
            <div class="post-skills-section">
                <span class="post-label">Skills needed</span>

                <div class="post-skills">
                    <?php foreach (skills_to_array($p['required_skills']) as $skill): ?>
                        <span class="skill-tag"><?= e($skill) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($p['location'])): ?>
            <div class="post-location">
                <span aria-hidden="true">⌖</span>
                <span><?= e($p['location']) ?></span>
            </div>
        <?php endif; ?>
    </div>

    <div class="post-card-footer">
        <div class="post-creator">
            <span class="creator-avatar">
                <?= e(initials($p['full_name'])) ?>
            </span>

            
            <div class="creator-info">
                <span class="creator-label">Posted by</span>
                <a
                    class="creator-name creator-profile-link"
                    href="/559-Capstone-Mohawk-capstone-LocalConnect/user-profile.php?id=<?= (int) $p['user_id'] ?>"
                >
                    <?= e($p['full_name']) ?>
                </a>
            </div>

        </div>

        <?php if (is_logged_in() && current_user()['id'] == $p['user_id']): ?>
            <div class="post-actions">
                <a
                    href="/559-Capstone-Mohawk-capstone-LocalConnect/post-edit.php?id=<?= (int)$p['id'] ?>"
                    class="edit-btn btn"
                >
                    Edit
                </a>

                <form
                    method="POST"
                    action="/559-Capstone-Mohawk-capstone-LocalConnect/post-delete.php"
                    class="post-delete-form"
                >
                    <?= csrf_field() ?>

                    <input
                        type="hidden"
                        name="post_id"
                        value="<?= (int)$p['id'] ?>"
                    >

                    <button
                        type="submit"
                        class="delete-btn btn"
                        onclick="return confirm('Are you sure you want to delete this collaboration?');"
                    >
                        Delete
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>

</div>
