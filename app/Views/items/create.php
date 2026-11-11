<?php declare(strict_types=1); ?>
<section class="intro">
    <h2>Report a <?= e($status) ?> item</h2>
    <p>Share enough detail to help identify and return the item.</p>
</section>
<section class="section auth-card">
    <?php if ($error !== null): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form action="/items" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="itemStatus" value="<?= e($status) ?>">
        <label for="itemType">Category</label>
        <select id="itemType" name="itemType" required>
            <option value="identification">Identification card</option>
            <option value="electronics">Electronic device</option>
            <option value="personal">Personal item</option>
        </select>
        <label for="itemName">Item name</label>
        <input id="itemName" name="itemName" maxlength="255" required>
        <label for="location">Location</label>
        <input id="location" name="location" maxlength="255" required>
        <label for="dateTime">Date and time</label>
        <input id="dateTime" name="dateTime" type="datetime-local" required>
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required></textarea>
        <label for="phoneNumber">Contact phone</label>
        <input id="phoneNumber" name="phoneNumber" type="tel" maxlength="50" required>
        <label for="itemImage">Photo (optional, max 5 MB)</label>
        <input id="itemImage" name="itemImage" type="file" accept="image/jpeg,image/png,image/gif,image/webp">
        <button type="submit">Submit report</button>
    </form>
</section>
