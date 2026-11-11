<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

final class User
{
    public function __construct(private readonly PDO $database)
    {
    }

    public function findByUsername(string $username): ?array
    {
        $statement = $this->database->prepare(
            'SELECT id, username, email, phone, `password`, last_login
             FROM users
             WHERE username = :username
             LIMIT 1'
        );
        $statement->execute(['username' => $username]);
        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->database->prepare(
            'SELECT id, username, email, phone, last_login
             FROM users
             WHERE id = :id
             LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    public function create(string $username, string $email, string $phone, string $password): int
    {
        $statement = $this->database->prepare(
            'INSERT INTO users (username, email, phone, `password`)
             VALUES (:username, :email, :phone, :password)'
        );
        $statement->execute([
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return (int) $this->database->lastInsertId();
    }
}
