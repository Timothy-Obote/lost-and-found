<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index_loggedin.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="home.css">

</head>
<body class="home-page">
    <header class="site-header">
        <nav class="account-nav" aria-label="Account">
            <a href="login.html">Log in</a>
            <a class="primary-link" href="register.html">Create account</a>
        </nav>
        <h1 class="brand">LOSE IT | LIST IT | FIND IT</h1>
        <nav class="submit-nav" aria-label="Post an item">
            <a href="submit-lost.html">Report lost</a>
            <a href="submit-found.html">Report found</a>
        </nav>
    </header>

    <main>
        <section class="intro">
            <h2>Find what's lost. Help return what's found.</h2>
            <p>Browse reported items by category, or share details about something you’ve lost or found.</p>
        </section>

        <section class="container category-grid" aria-label="Browse item categories">
            <article class="section category-card" id="identification-cards">
                <a href="identification_cards.php">
                    <h3>Identification cards</h3>
                    <p>Browse IDs, campus cards, passports, and other identification.</p>
                </a>
            </article>
            <article class="section category-card" id="electronic-devices">
                <a href="electronics.php">
                    <h3>Electronic devices</h3>
                    <p>Look for phones, laptops, and other electronic devices.</p>
                </a>
            </article>
            <article class="section category-card" id="personal-items">
                <a href="personal_items.php">
                    <h3>Personal items</h3>
                    <p>Browse bags, water bottles, keys, and other belongings.</p>
                </a>
            </article>
        </section>
    </main>
</body>
</html>
