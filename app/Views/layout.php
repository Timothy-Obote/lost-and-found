<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Lost and Found') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="stylesheet" href="/assets/home.css">
</head>
<body class="home-page">
    <header class="site-header">
        <nav class="account-nav" aria-label="Account">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="/profile">My profile</a>
                <form action="/logout" method="post">
                    <?= csrf_field() ?>
                    <button class="primary-link" type="submit">Log out</button>
                </form>
            <?php else: ?>
                <a href="/login">Log in</a>
                <a class="primary-link" href="/register">Create account</a>
            <?php endif; ?>
        </nav>
        <h1 class="brand"><a href="/">LOSE IT | LIST IT | FIND IT</a></h1>
        <nav class="submit-nav" aria-label="Post an item">
            <a href="/items/new?status=lost">Report lost</a>
            <a href="/items/new?status=found">Report found</a>
        </nav>
    </header>
    <main>
        <?php require $viewFile; ?>
    </main>
</body>
</html>
