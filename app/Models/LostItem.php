<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

final class LostItem
{
    private const CATEGORIES = [
        'identification' => 'identification',
        'electronic' => 'electronics',
        'electronics' => 'electronics',
        'personal' => 'personal',
    ];

    public function __construct(private readonly PDO $database)
    {
    }

    public static function normalizeCategory(string $category): ?string
    {
        return self::CATEGORIES[$category] ?? null;
    }

    public function all(): array
    {
        $statement = $this->database->query(
            'SELECT item_type, itemName, location, date_time, image, description
             FROM lost_items
             ORDER BY date_time DESC'
        );

        return $statement->fetchAll();
    }

    public function byCategory(string $category): array
    {
        $storedCategory = $category === 'electronics' ? 'electronic' : $category;
        $statement = $this->database->prepare(
            'SELECT itemName, location, date_time, image, description
             FROM lost_items
             WHERE item_type IN (:category, :alternate)
             ORDER BY date_time DESC'
        );
        $statement->execute([
            'category' => $storedCategory,
            'alternate' => $category,
        ]);

        return $statement->fetchAll();
    }

    public function create(
        int $userId,
        string $category,
        string $name,
        string $location,
        string $description,
        ?string $image,
        string $dateTime,
        string $phone
    ): void {
        $statement = $this->database->prepare(
            'INSERT INTO lost_items
                (user_id, item_type, itemName, location, description, image, date_time, phone_number)
             VALUES
                (:user_id, :item_type, :item_name, :location, :description, :image, :date_time, :phone)'
        );
        $statement->execute([
            'user_id' => $userId,
            'item_type' => $category,
            'item_name' => $name,
            'location' => $location,
            'description' => $description,
            'image' => $image,
            'date_time' => $dateTime,
            'phone' => $phone,
        ]);
    }
}
