<?php
declare(strict_types=1);

namespace App\Controllers;

final class HomeController
{
    public function index(): void
    {
        render('home/index', [
            'title' => 'Lost and Found',
            'loggedIn' => isset($_SESSION['user_id']),
        ]);
    }
}
