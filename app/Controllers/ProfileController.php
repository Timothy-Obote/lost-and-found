<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;

final class ProfileController
{
    public function __construct(private readonly User $users)
    {
    }

    public function show(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login?next=/profile');
        }

        $user = $this->users->findById((int) $_SESSION['user_id']);
        if ($user === null) {
            http_response_code(404);
            render('errors/not-found', ['title' => 'Profile not found']);
            return;
        }

        render('profile/show', [
            'title' => 'My profile',
            'user' => $user,
        ]);
    }
}
