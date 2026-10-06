<div class="card post-card">

    <h3><?= e($p['title']) ?></h3>

    <p>
        <?= e($p['description']) ?>
    </p>

    <?php if (!empty($p['required_skills'])): ?>
        <p>
            <strong>Required Skills:</strong>
            <?= e($p['required_skills']) ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($p['location'])): ?>
        <p>
            <strong>Location:</strong>
            <?= e($p['location']) ?>
        </p>
    <?php endif; ?>

    <p class="muted">
        Posted by <?= e($p['full_name']) ?>
    </p>

    <p class="muted">
        <?= e(format_date($p['created_at'])) ?>
    </p>

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
                action="/post-delete.php"
                style="display:inline;"
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