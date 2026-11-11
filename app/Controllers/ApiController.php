<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\LostItem;

final class ApiController
{
    public function __construct(private readonly LostItem $items)
    {
    }

    public function items(): void
    {
        $data = [
            'identification_cards' => [],
            'electronic_devices' => [],
            'personal_items' => [],
        ];

        foreach ($this->items->all() as $item) {
            $category = LostItem::normalizeCategory((string) $item['item_type']);
            if (!empty($item['image'])) {
                $item['image'] = '/uploads/' . rawurlencode(basename((string) $item['image']));
            }

            $key = match ($category) {
                'identification' => 'identification_cards',
                'electronics' => 'electronic_devices',
                'personal' => 'personal_items',
                default => null,
            };

            if ($key !== null) {
                $data[$key][] = $item;
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}
