<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\LostItem;
use DateTimeImmutable;
use finfo;
use RuntimeException;

final class ItemController
{
    public function __construct(private readonly LostItem $items)
    {
    }

    public function category(string $category): void
    {
        $category = LostItem::normalizeCategory($category) ?? '';
        if ($category === '') {
            http_response_code(404);
            render('errors/not-found', ['title' => 'Not found']);
            return;
        }

        $items = $this->items->byCategory($category);
        $locations = array_values(array_unique(array_column($items, 'location')));
        render('items/category', [
            'title' => ucfirst($category),
            'category' => $category,
            'items' => $items,
            'locations' => $locations,
        ]);
    }

    public function createForm(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $status = (string) ($_GET['status'] ?? 'lost');
        if (!in_array($status, ['lost', 'found'], true)) {
            $status = 'lost';
        }

        render('items/create', [
            'title' => 'Report an item',
            'error' => null,
            'status' => $status,
        ]);
    }

    public function create(): void
    {
        verify_csrf();

        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $category = LostItem::normalizeCategory((string) ($_POST['itemType'] ?? ''));
        $status = (string) ($_POST['itemStatus'] ?? '');
        $name = trim((string) ($_POST['itemName'] ?? ''));
        $location = trim((string) ($_POST['location'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $dateInput = (string) ($_POST['dateTime'] ?? '');
        $phone = trim((string) ($_POST['phoneNumber'] ?? ''));

        if (
            $category === null
            || !in_array($status, ['lost', 'found'], true)
            || $name === ''
            || $location === ''
            || $description === ''
            || $dateInput === ''
            || $phone === ''
        ) {
            $this->showCreateError('Complete all required fields with valid values.', $status);
            return;
        }

        $dateTime = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $dateInput);
        $dateErrors = DateTimeImmutable::getLastErrors();
        if (
            $dateTime === false
            || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))
        ) {
            $this->showCreateError('Enter a valid date and time.', $status);
            return;
        }

        try {
            $image = $this->storeImage($_FILES['itemImage'] ?? null);
            $this->items->create(
                (int) $_SESSION['user_id'],
                $category,
                $name,
                $location,
                $description,
                $image,
                $dateTime->format('Y-m-d H:i:s'),
                $phone
            );
        } catch (RuntimeException $exception) {
            $this->showCreateError($exception->getMessage(), $status);
            return;
        }

        redirect('/items/' . rawurlencode($category));
    }

    private function storeImage(mixed $file): ?string
    {
        if (!is_array($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('The image upload did not complete successfully.');
        }
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new RuntimeException('Images must be 5 MB or smaller.');
        }

        $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file((string) $file['tmp_name']);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];
        if (!isset($extensions[$mimeType])) {
            throw new RuntimeException('Use a JPG, PNG, GIF, or WebP image.');
        }

        $directory = dirname(__DIR__, 2) . '/uploads';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('The image upload directory could not be created.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
        if (!move_uploaded_file((string) $file['tmp_name'], $directory . '/' . $filename)) {
            throw new RuntimeException('The image could not be saved.');
        }

        return '/uploads/' . $filename;
    }

    private function showCreateError(string $error, string $status): void
    {
        http_response_code(422);
        render('items/create', [
            'title' => 'Report an item',
            'error' => $error,
            'status' => in_array($status, ['lost', 'found'], true) ? $status : 'lost',
        ]);
    }
}
