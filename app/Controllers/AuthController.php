<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use PDOException;

final class AuthController
{
    public function __construct(private readonly User $users)
    {
    }

    public function loginForm(): void
    {
        render('auth/login', [
            'title' => 'Log in',
            'error' => null,
        ]);
    }

    public function login(): void
    {
        verify_csrf();
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = $username === '' ? null : $this->users->findByUsername($username);

        if ($user === null || !password_verify($password, $user['password'])) {
            http_response_code(422);
            render('auth/login', [
                'title' => 'Log in',
                'error' => 'The username or password was not recognized.',
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        redirect('/');
    }

    public function registerForm(): void
    {
        render('auth/register', [
            'title' => 'Create account',
            'error' => null,
            'values' => ['username' => '', 'email' => '', 'phone' => ''],
        ]);
    }

    public function register(): void
    {
        verify_csrf();
        $values = [
            'username' => trim((string) ($_POST['username'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
        ];
        $password = (string) ($_POST['password'] ?? '');

        if (
            $values['username'] === ''
            || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)
            || $values['phone'] === ''
            || strlen($password) < 8
        ) {
            http_response_code(422);
            render('auth/register', [
                'title' => 'Create account',
                'error' => 'Enter a username, valid email, phone number, and password of at least 8 characters.',
                'values' => $values,
            ]);
            return;
        }

        try {
            $userId = $this->users->create(
                $values['username'],
                $values['email'],
                $values['phone'],
                $password
            );
        } catch (PDOException $exception) {
            if ($exception->getCode() !== '23000') {
                throw $exception;
            }

            http_response_code(409);
            render('auth/register', [
                'title' => 'Create account',
                'error' => 'That username or email is already in use.',
                'values' => $values,
            ]);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $values['username'];
        redirect('/');
    }

    public function logout(): void
    {
        verify_csrf();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $cookie = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $cookie['path'],
                'domain' => $cookie['domain'],
                'secure' => $cookie['secure'],
                'httponly' => $cookie['httponly'],
                'samesite' => $cookie['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
        redirect('/');
    }
}
