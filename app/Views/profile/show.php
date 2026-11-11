<?php declare(strict_types=1); ?>
<section class="intro">
    <h2>My profile</h2>
    <p>Your account information.</p>
</section>
<section class="section auth-card">
    <p><strong>Username:</strong> <?= e($user['username']) ?></p>
    <p><strong>Email:</strong> <?= e($user['email']) ?></p>
    <p><strong>Phone:</strong> <?= e($user['phone']) ?></p>
    <p><strong>Last login:</strong> <?= e($user['last_login'] ?? 'Not recorded') ?></p>
    <a href="/">Back to homepage</a>
</section>
