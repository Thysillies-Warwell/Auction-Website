<?php
require_once 'database.php';

class member {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function emailExists(string $email): bool {
        $query = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

public function create(string $username, string $email, string $password): bool {
    $query = "
        INSERT INTO users (username, email, password_hash)
        VALUES (:username, :email, :password_hash)
    ";

    $stmt = $this->pdo->prepare($query);

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':password_hash', $passwordHash, PDO::PARAM_STR);

    return $stmt->execute();
}

public function getByEmail(string $email): array|false {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch();
}

public function deleteAccount(int $userId): bool {
    $query = "DELETE FROM users WHERE user_id = :user_id";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

    return $stmt->execute();
}

}