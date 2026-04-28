<?php
require_once 'database.php';

class listing {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        $query = "SELECT * FROM listing";
        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll();
    }

    public function search(string $searchTerm): array {
    $query = "
        SELECT * FROM listing
        WHERE title LIKE :titleSearch
           OR description LIKE :descriptionSearch
    ";

    $stmt = $this->pdo->prepare($query);

    $likeTerm = '%' . $searchTerm . '%';

    $stmt->bindValue(':titleSearch', $likeTerm, PDO::PARAM_STR);
    $stmt->bindValue(':descriptionSearch', $likeTerm, PDO::PARAM_STR);

    $stmt->execute();

    return $stmt->fetchAll();
}

    public function getFeatured(int $limit = 4): array {
        $query = "SELECT * FROM listing ORDER BY listing_id DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

public function create(int $userId, string $title, string $description, float $startingPrice): bool {
    $query = "
        INSERT INTO listing 
        (user_id, title, description, starting_price, current_price, ends_at)
        VALUES 
        (:user_id, :title, :description, :starting_price, :current_price, :ends_at)
    ";

    $stmt = $this->pdo->prepare($query);

    $endsAt = date('Y-m-d H:i:s', strtotime('+7 days'));

    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->bindValue(':starting_price', $startingPrice);
    $stmt->bindValue(':current_price', $startingPrice);
    $stmt->bindValue(':ends_at', $endsAt, PDO::PARAM_STR);

    return $stmt->execute();
}

public function getByUser(int $userId): array {
    $query = "SELECT * FROM listing WHERE user_id = :user_id ORDER BY listing_id DESC";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

public function getById(int $listingId): array|false {
    $query = "SELECT * FROM listing WHERE listing_id = :listing_id";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':listing_id', $listingId, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch();
}

public function update(int $listingId, int $userId, string $title, string $description, float $startingPrice): bool {
    $query = "
        UPDATE listing
        SET title = :title,
            description = :description,
            starting_price = :starting_price,
            current_price = :current_price
        WHERE listing_id = :listing_id
          AND user_id = :user_id
    ";

    $stmt = $this->pdo->prepare($query);

    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->bindValue(':starting_price', $startingPrice);
    $stmt->bindValue(':current_price', $startingPrice);
    $stmt->bindValue(':listing_id', $listingId, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

    return $stmt->execute();
}

public function delete(int $listingId, int $userId): bool {
    $query = "
        DELETE FROM listing
        WHERE listing_id = :listing_id
          AND user_id = :user_id
    ";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':listing_id', $listingId, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

    return $stmt->execute();
}


public function placeBid(int $listingId, int $userId, float $bidAmount): bool {

    // get current listing
    $query = "SELECT current_price FROM listing WHERE listing_id = :listing_id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':listing_id', $listingId, PDO::PARAM_INT);
    $stmt->execute();

    $listing = $stmt->fetch();

    if (!$listing) return false;

    // bid must be higher
    if ($bidAmount <= $listing['current_price']) {
        return false;
    }

    // insert bid
    $query = "
        INSERT INTO bids (listing_id, user_id, bid_amount)
        VALUES (:listing_id, :user_id, :bid_amount)
    ";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':listing_id', $listingId, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':bid_amount', $bidAmount);

    $stmt->execute();

    // update listing price
    $query = "
        UPDATE listing
        SET current_price = :bid_amount
        WHERE listing_id = :listing_id
    ";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':bid_amount', $bidAmount);
    $stmt->bindValue(':listing_id', $listingId, PDO::PARAM_INT);

    return $stmt->execute();
}

public function getUserBid(int $listingId, int $userId): ?float {
    $query = "
        SELECT bid_amount 
        FROM bids 
        WHERE listing_id = :listing_id 
          AND user_id = :user_id
        ORDER BY bid_id DESC
        LIMIT 1
    ";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':listing_id', $listingId, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch();

    return $result ? (float)$result['bid_amount'] : null;
}


}