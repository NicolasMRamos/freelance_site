<?php
declare(strict_types=1);

class Orders {
    public int    $order_id;
    public string $status;
    public string $order_date;
    public int    $client_id;
    public int    $service_id;

    public function __construct(int $order_id, string $status, string $order_date, int $client_id, int $service_id) {
        $this->order_id = $order_id;
        $this->status = $status;
        $this->order_date = $order_date;
        $this->client_id  = $client_id;
        $this->service_id = $service_id;
    }


    public static function getOrderFromId(PDO $db, int $order_id): Orders {
        $stmt = $db->prepare('SELECT order_id, status, order_date, client_id, service_id
                             FROM Orders
                             WHERE order_id = ?');

        $stmt->execute([$order_id]);
        $order = $stmt->fetch();

        return new Orders(
            (int)$order['order_id'],
            (string)$order['status'],
            (string)$order['order_date'],
            (int)$order['client_id'],
            (int)$order['service_id']
        );
    }

    public static function getOrdersFromService(PDO $db, int $service_id): array {
        $stmt = $db->prepare('SELECT order_id, status, order_date, client_id, service_id
                             FROM Orders
                             WHERE service_id = ?
                             ORDER BY order_date DESC');

        $stmt->execute([$service_id]);

        $orders = array();
        while ($order = $stmt->fetch()) {
            $orders[] = new Orders(
                (int)$order['order_id'],
                (string)$order['status'],
                (string)$order['order_date'],
                (int)$order['client_id'],
                (int)$order['service_id']
            );
        }
        return $orders;
    }

    public static function getOrdersFromClient(PDO $db, int $client_id): array {
        $stmt = $db->prepare('SELECT order_id, status, order_date, client_id, service_id
                              FROM Orders
                              WHERE client_id = ?
                              ORDER BY order_date DESC');
        $stmt->execute([$client_id]);

        $orders = array();
        while ($order = $stmt->fetch()) {
            $orders[] = new Orders(
                (int)$order['order_id'],
                (string)$order['status'],
                (string)$order['order_date'],
                (int)$order['client_id'],
                (int)$order['service_id']
            );
        }
        return $orders;
    }

    public static function createOrder(PDO $db, int $client_id, int $service_id): int {
        $stmt = $db->prepare('INSERT INTO Orders (status, order_date, client_id, service_id) VALUES (?, datetime("now"), ?, ?)');
        $status = "pending";
        $stmt->execute([$status, $client_id, $service_id]);
        return (int)$db->lastInsertId();
    }

}
?>
