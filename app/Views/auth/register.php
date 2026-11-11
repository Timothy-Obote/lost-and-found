<?php declare(strict_types=1); ?>
<section class="intro">
    <h2>Create an account</h2>
    <p>Register to report lost or found items.</p>
</section>
<section class="section auth-card">
    <?php if ($error !== null): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form action="/register" method="post">
        <?= csrf_field() ?>
        <label for="username">Username</label>
        <input id="username" name="username" type="text" maxlength="100" autocomplete="username" value="<?= e($values['username']) ?>" required>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" maxlength="254" autocomplete="email" value="<?= e($values['email']) ?>" required>
        <label for="phone">Phone</label>
        <input id="phone" name="phone" type="tel" maxlength="50" autocomplete="tel" value="<?= e($values['phone']) ?>" required>
        <label for="password">Password (at least 8 characters)</label>
        <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required>
        <button type="submit">Create account</button>
    </form>
</section>
