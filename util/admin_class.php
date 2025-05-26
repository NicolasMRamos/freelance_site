<?php
  declare(strict_types = 1);
  require_once(__DIR__ . '/../util/user_class.php');

  class Admin extends User {
        public function promoteUser(PDO $db, int $userId): void {
        $stmt = $db->prepare('UPDATE Users SET is_admin = 1 WHERE user_id = ?');
        $stmt->execute([$userId]);
    }

    public function demoteUser(PDO $db, int $userId): void {
        $stmt = $db->prepare('UPDATE Users SET is_admin = 0 WHERE user_id = ?');
        $stmt->execute([$userId]);
    }

    public function deleteUser(PDO $db, int $userId): void {
        $stmt = $db->prepare('SELECT 1 FROM Users WHERE user_id = ?');
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            throw new Exception('User does not exist');
        }
        $stmt = $db->prepare('DELETE FROM Users WHERE user_id = ?');
        $stmt->execute([$userId]);
    }

    public function deleteService(PDO $db, int $serviceId): void {
        $stmt = $db->prepare('SELECT 1 FROM Services WHERE service_id = ?');
        $stmt->execute([$serviceId]);
        if (!$stmt->fetch()) {
            throw new Exception('Service does not exist');
        }
        $stmt = $db->prepare('DELETE FROM Services WHERE service_id = ?');
        $stmt->execute([$serviceId]);
    }

    public function deleteReview(PDO $db, int $reviewId): void {
        $stmt = $db->prepare('SELECT 1 FROM Reviews WHERE review_id = ?');
        $stmt->execute([$reviewId]);
        if (!$stmt->fetch()) {
            throw new Exception('Review does not exist');
        }
        $stmt = $db->prepare('DELETE FROM Reviews WHERE review_id = ?');
        $stmt->execute([$reviewId]);
    }

    public function deleteMessage(PDO $db, int $messageId): void {
        $stmt = $db->prepare('SELECT 1 FROM Messages WHERE message_id = ?');
        $stmt->execute([$messageId]);
        if (!$stmt->fetch()) {
            throw new Exception('Message does not exist');
        }
        $stmt = $db->prepare('DELETE FROM Messages WHERE message_id = ?');
        $stmt->execute([$messageId]);
    }

    public function deleteOrder(PDO $db, int $orderId): void {
        $stmt = $db->prepare('SELECT 1 FROM Orders WHERE order_id = ?');
        $stmt->execute([$orderId]);
        if (!$stmt->fetch()) {
            throw new Exception('Order does not exist');
        }
        $stmt = $db->prepare('DELETE FROM Orders WHERE order_id = ?');
        $stmt->execute([$orderId]);
    }

    public function deleteCustomOrder(PDO $db, int $customOrderId): void {
        $stmt = $db->prepare('SELECT 1 FROM CustomOrders WHERE custom_order_id = ?');
        $stmt->execute([$customOrderId]);
        if (!$stmt->fetch()) {
            throw new Exception('Custom order does not exist');
        }
        $stmt = $db->prepare('DELETE FROM CustomOrders WHERE custom_order_id = ?');
        $stmt->execute([$customOrderId]);
    }

    public function addCategory(PDO $db, string $name): void {
        $stmt = $db->prepare('SELECT 1 FROM Categories WHERE name = ?');
        $stmt->execute([$name]);
        if ($stmt->fetch()) {
            throw new Exception('Category already exists');
        }
        $stmt = $db->prepare('INSERT INTO Categories (name) VALUES (?)');
        $stmt->execute([$name]);
    }

    public function deleteCategory(PDO $db, int $categoryId): void {
        $stmt = $db->prepare('SELECT 1 FROM Categories WHERE name = ?');
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            throw new Exception('Category does not exist');
        }
        $stmt = $db->prepare('DELETE FROM Categories WHERE category_id = ?');
        $stmt->execute([$categoryId]);
    }
  }
?>
