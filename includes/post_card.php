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
                    href="/user-profile.php?id=<?= (int) $p['user_id'] ?>"
                >
                    <?= e($p['full_name']) ?>
                </a>
            </div>

        </div>

        <?php if (is_logged_in() && current_user()['id'] == $p['user_id']): ?>
            <div class="post-actions">
                <a
                    href="/post-edit.php?id=<?= (int)$p['id'] ?>"
                    class="edit-btn btn"
                >
                    Edit
                </a>

                <form
                    method="POST"
                    action="/post-delete.php"
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
        
    <div class="post-request-section">

    <?php if (!is_logged_in()): ?>

        <a href="/login.php" class="btn btn-primary">
            Log in to Send Request
        </a>

    <?php elseif ((int) current_user()['id'] !== (int) $p['user_id']): ?>

        <?php if (($p['my_request_status'] ?? null) === 'pending'): ?>

            <div class="request-status request-pending">
                <strong>✓ Request Sent Successfully!</strong>
                <p>Your collaboration request is awaiting a response.</p>
                <span>Status: Pending</span>
            </div>

        <?php elseif (($p['my_request_status'] ?? null) === 'accepted'): ?>

            <div class="request-status request-accepted">
                <strong>✓ Request Accepted!</strong>
                <p>Your collaboration request has been accepted.</p>
            </div>

        <?php elseif (($p['my_request_status'] ?? null) === 'declined'): ?>

            <div class="request-status request-declined">
                <strong>Request Declined</strong>
                <p>Your previous request was declined. You can try again.</p>

                <form method="POST" action="/send-request.php">
                    <?= csrf_field() ?>
                    <input type="hidden" name="post_id" value="<?= (int) $p['id'] ?>">

                    <label for="request-message-<?= (int) $p['id'] ?>">
                        Personal message (optional)
                    </label>

                    <textarea
                        id="request-message-<?= (int) $p['id'] ?>"
                        name="message"
                        maxlength="1000"
                        placeholder="Tell the post owner why you'd like to collaborate..."
                    ></textarea>

                    <button type="submit" class="btn btn-primary">
                        Send Another Request
                    </button>
                </form>
            </div>

        <?php else: ?>

            <form method="POST" action="/send-request.php">
                <?= csrf_field() ?>

                <input type="hidden" name="post_id" value="<?= (int) $p['id'] ?>">

                <label for="request-message-<?= (int) $p['id'] ?>">
                    Personal message (optional)
                </label>

                <textarea
                    id="request-message-<?= (int) $p['id'] ?>"
                    name="message"
                    maxlength="1000"
                    placeholder="Tell the post owner why you'd like to collaborate..."
                ></textarea>

                <button type="submit" class="btn btn-primary">
                    Send Request
                </button>
            </form>

        <?php endif; ?>

    <?php endif; ?>

</div>

</div>
