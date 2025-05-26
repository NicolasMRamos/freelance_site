<?php
declare(strict_types=1);

class Reviews {
    public int $review_id;
    public int $rating;
    public string $review_title;
    public string $review_text;
    public string $review_date;
    public int $client_id;
    public int $service_id;

    public function __construct(int $review_id, int $rating, string $review_title, string $review_text, string $review_date, int $client_id, int $service_id) {
        $this->review_id = $review_id;
        $this->rating = $rating;
        $this->review_title = $review_title;
        $this->review_text = $review_text;
        $this->review_date = $review_date;
        $this->client_id = $client_id;
        $this->service_id = $service_id;
    }

    public static function getReviewFromId(PDO $db, int $id): Reviews {
        $stmt = $db->prepare('SELECT review_id, rating, review_title, review_text, review_date, client_id, service_id
                                FROM Reviews
                                WHERE review_id = ?');

        $stmt->execute([$id]);
        $review = $stmt->fetch();

        return new Reviews(
            (int)$review['review_id'],
            (int)$review['rating'],
            (string)$review['review_title'],
            (string)$review['review_text'],
            (string)$review['review_date'],
            (int)$review['client_id'],
            (int)$review['service_id']
        );
    }

    public static function getReviewsFromService(PDO $db, int $service_id): array {
        $stmt = $db->prepare('SELECT review_id, rating, review_title, review_text, review_date, client_id, service_id
                              FROM Reviews
                              WHERE service_id = ?
                              ORDER BY review_date DESC, review_id DESC');
        $stmt->execute([$service_id]);

        $reviews = array();
        while ($review = $stmt->fetch()) {
            $reviews[] = new Reviews(
                (int)$review['review_id'],
                (int)$review['rating'],
                (string)$review['review_title'],
                (string)$review['review_text'],
                (string)$review['review_date'],
                (int)$review['client_id'],
                (int)$review['service_id']
            );
        }
        return $reviews;
    }

   
    public static function getReviewFromClient(PDO $db, int $client_id): array {
        $stmt = $db->prepare('SELECT review_id, rating, review_title, review_text, review_date, client_id, service_id
                            FROM Reviews
                            WHERE client_id = ?
                            ORDER BY review_date DESC, review_id DESC');
        $stmt->execute([$client_id]);

        $reviews = [];
        while ($review = $stmt->fetch()) {
            $reviews[] = new Reviews(
                (int)$review['review_id'],
                (int)$review['rating'],
                (string)$review['review_title'],
                (string)$review['review_text'],
                (string)$review['review_date'],
                (int)$review['client_id'],
                (int)$review['service_id']
            );
        }
        return $reviews;
    }

    public function getClientName(PDO $db): string {
        $stmt = $db->prepare('SELECT username FROM Users WHERE user_id = ?');
        $stmt->execute([$this->client_id]);

        $username = $stmt->fetch();
        return (string)$username['username'];
    }


    public static function getAverageRating(PDO $db, int $service_id): ?float {
        $stmt = $db->prepare('SELECT AVG(rating) AS avg_rating
                                FROM Reviews
                                WHERE service_id = ?');

        $stmt->execute([$service_id]);
        $avg = $stmt->fetchColumn();

        if ($avg === false || $avg === null) {
            return null;
        }

        return (float)$avg;
    }

    public static function createReview(PDO $db, int $rating, string $review_title, string $review_text, int $client_id, int $service_id){
        $stmt = $db->prepare('INSERT INTO Reviews (rating, review_title, review_text, client_id, service_id)
                                VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $rating,
            $review_title,
            $review_text,
            $client_id,
            $service_id
        ]);
    }

}
?>
