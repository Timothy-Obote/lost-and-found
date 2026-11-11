<?php declare(strict_types=1); ?>
<section class="intro">
    <h2>Log in</h2>
    <p>Sign in to post a lost or found item.</p>
</section>
<section class="section auth-card">
    <?php if ($error !== null): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form action="/login" method="post">
        <?= csrf_field() ?>
        <label for="username">Username</label>
        <input id="username" name="username" type="text" autocomplete="username" required>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <button type="submit">Log in</button>
    </form>
    <p>New here? <a href="/register">Create an account</a>.</p>
</section>
