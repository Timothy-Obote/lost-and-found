<?php declare(strict_types=1); ?>
<section class="intro">
    <h2><?= e(match ($category) {
        'identification' => 'Identification cards',
        'electronics' => 'Electronic devices',
        default => 'Personal items',
    }) ?></h2>
    <p>Search reports by item name or filter by location.</p>
</section>
<section class="filter-container" aria-label="Filter reports">
    <label class="visually-hidden" for="item-search">Search items</label>
    <input id="item-search" type="search" placeholder="Search item names">
    <label class="visually-hidden" for="location-filter">Filter by location</label>
    <select id="location-filter">
        <option value="">All locations</option>
        <?php foreach ($locations as $location): ?>
            <option value="<?= e(mb_strtolower($location)) ?>"><?= e($location) ?></option>
        <?php endforeach; ?>
    </select>
</section>
<section class="container items-grid" aria-label="Item reports">
    <?php if ($items === []): ?>
        <p>No reports in this category yet.</p>
    <?php endif; ?>
    <?php foreach ($items as $item): ?>
        <article class="section item-card"
            data-name="<?= e(mb_strtolower($item['itemName'])) ?>"
            data-location="<?= e(mb_strtolower($item['location'])) ?>">
            <?php if (!empty($item['image'])): ?>
                <img src="/uploads/<?= e(rawurlencode(basename((string) $item['image']))) ?>" alt="Photo of <?= e($item['itemName']) ?>">
            <?php endif; ?>
            <h3><?= e($item['itemName']) ?></h3>
            <p><strong>Location:</strong> <?= e($item['location']) ?></p>
            <p><strong>Date:</strong> <?= e($item['date_time']) ?></p>
            <p><?= nl2br(e($item['description'])) ?></p>
        </article>
    <?php endforeach; ?>
</section>
<script>
    const search = document.querySelector('#item-search');
    const locationFilter = document.querySelector('#location-filter');
    const cards = document.querySelectorAll('.item-card');
    function filterItems() {
        const query = search.value.toLowerCase();
        const location = locationFilter.value;
        cards.forEach((card) => {
            card.hidden = !card.dataset.name.includes(query)
                || (location !== '' && card.dataset.location !== location);
        });
    }
    search.addEventListener('input', filterItems);
    locationFilter.addEventListener('change', filterItems);
</script>
