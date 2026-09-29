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

</div>