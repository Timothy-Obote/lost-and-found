<?php declare(strict_types=1); ?>
<section class="intro">
    <h2><?= !empty($loggedIn) ? 'Welcome back' : "Find what's lost. Help return what's found." ?></h2>
    <p>Browse reported items by category, or share details about something you've lost or found.</p>
</section>

<section class="container category-grid" aria-label="Browse item categories">
    <?php foreach ([
        'identification' => ['identification-cards', 'Identification cards', 'Browse IDs, campus cards, passports, and other identification.'],
        'electronics' => ['electronic-devices', 'Electronic devices', 'Look for phones, laptops, and other electronic devices.'],
        'personal' => ['personal-items', 'Personal items', 'Browse bags, water bottles, keys, and other belongings.'],
    ] as $category => [$sectionId, $name, $description]): ?>
        <article class="section category-card" id="<?= e($sectionId) ?>">
            <a href="/items/<?= e($category) ?>">
                <h3><?= e($name) ?></h3>
                <p><?= e($description) ?></p>
            </a>
        </article>
    <?php endforeach; ?>
</section>
<?php if (!empty($loggedIn)): ?>
<script>
    fetch('/api/items')
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Item feed request failed (${response.status}).`);
            }
            return response.json();
        })
        .then((groups) => {
            const sections = {
                identification_cards: 'identification-cards',
                electronic_devices: 'electronic-devices',
                personal_items: 'personal-items',
            };

            Object.entries(sections).forEach(([group, sectionId]) => {
                const section = document.getElementById(sectionId);
                (groups[group] ?? []).forEach((item) => {
                    const report = document.createElement('div');
                    report.className = 'lost-item';
                    const name = document.createElement('h3');
                    name.textContent = item.itemName;
                    const location = document.createElement('p');
                    location.textContent = `Location: ${item.location}`;
                    const date = document.createElement('p');
                    date.textContent = `Date and time: ${item.date_time}`;
                    const description = document.createElement('p');
                    description.textContent = item.description;
                    report.append(name, location, date, description);

                    if (typeof item.image === 'string' && item.image.startsWith('/uploads/')) {
                        const image = document.createElement('img');
                        image.src = item.image;
                        image.alt = `Photo of ${item.itemName}`;
                        report.append(image);
                    }

                    section.append(report);
                });
            });
        })
        .catch((error) => console.error('Unable to load item feed:', error));
</script>
<?php endif; ?>
