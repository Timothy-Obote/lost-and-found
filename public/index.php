<?php
declare(strict_types=1);

use App\Controllers\ApiController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ItemController;
use App\Controllers\ProfileController;
use App\Core\Database;
use App\Models\LostItem;
use App\Models\User;

require dirname(__DIR__) . '/bootstrap.php';

try {
    $users = null;
    $items = null;
    $getUsers = static function () use (&$users): User {
        return $users ??= new User(Database::connection());
    };
    $getItems = static function () use (&$items): LostItem {
        return $items ??= new LostItem(Database::connection());
    };
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

    if ($method === 'GET' && in_array($path, ['/', '/home.html', '/home.php', '/index_loggedin.html'], true)) {
        (new HomeController())->index();
    } elseif ($method === 'GET' && in_array($path, ['/login', '/login.html', '/login.php'], true)) {
        (new AuthController($getUsers()))->loginForm();
    } elseif ($method === 'POST' && in_array($path, ['/login', '/login.php'], true)) {
        (new AuthController($getUsers()))->login();
    } elseif ($method === 'GET' && in_array($path, ['/register', '/register.html'], true)) {
        (new AuthController($getUsers()))->registerForm();
    } elseif ($method === 'POST' && in_array($path, ['/register', '/register.php'], true)) {
        (new AuthController($getUsers()))->register();
    } elseif ($method === 'POST' && in_array($path, ['/logout', '/logout.php'], true)) {
        (new AuthController($getUsers()))->logout();
    } elseif ($method === 'GET' && in_array($path, ['/profile', '/profile.php'], true)) {
        (new ProfileController($getUsers()))->show();
    } elseif ($method === 'GET' && in_array($path, ['/items/new', '/submit-lost.html', '/submit-found.html'], true)) {
        if ($path === '/submit-found.html') {
            $_GET['status'] = 'found';
        }
        $itemController->createForm();
    } elseif ($method === 'POST' && in_array($path, ['/items', '/submit_item.php'], true)) {
        (new ItemController($getItems()))->create();
    } elseif ($method === 'GET' && preg_match('#^/items/([a-z-]+)$#', $path, $matches) === 1) {
        (new ItemController($getItems()))->category($matches[1]);
    } elseif ($method === 'GET' && $path === '/identification_cards.php') {
        (new ItemController($getItems()))->category('identification');
    } elseif ($method === 'GET' && $path === '/electronics.php') {
        (new ItemController($getItems()))->category('electronics');
    } elseif ($method === 'GET' && $path === '/personal_items.php') {
        (new ItemController($getItems()))->category('personal');
    } elseif ($method === 'GET' && in_array($path, ['/api/items', '/display_items.php'], true)) {
        (new ApiController($getItems()))->items();
    } elseif (preg_match('#^/uploads/([a-zA-Z0-9._-]+)$#', $path, $matches) === 1 && $method === 'GET') {
        $file = dirname(__DIR__) . '/uploads/' . basename($matches[1]);
        if (!is_file($file)) {
            http_response_code(404);
            exit;
        }
        header('X-Content-Type-Options: nosniff');
        header('Content-Type: ' . (mime_content_type($file) ?: 'application/octet-stream'));
        readfile($file);
    } else {
        http_response_code(404);
        render('errors/not-found', ['title' => 'Page not found']);
    }
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    echo 'An unexpected server error occurred.';
}
