<?php
declare(strict_types=1);

class CustomOrders {
    public int $custom_order_id;
    public string $custom_title;
    public string $custom_desc;
    public float $custom_price;
    public string $order_date;
    public int $custom_delivery_time;
    public string $status;
    public int $client_id;
    public int $service_id;

    public function __construct(int $custom_order_id, string $custom_title, string $custom_desc, float $custom_price, string $order_date, int $custom_delivery_time, string $status,int $client_id, int $service_id)
     {
        $this->custom_order_id = $custom_order_id;
        $this->custom_title = $custom_title;
        $this->custom_desc = $custom_desc;
        $this->custom_price = $custom_price;
        $this->order_date = $order_date;
        $this->custom_delivery_time = $custom_delivery_time;
        $this->status = $status;
        $this->client_id = $client_id;
        $this->service_id = $service_id;
    }

    
    public static function getCustomOrderFromId(PDO $db, int $id): CustomOrders {
        $stmt = $db->prepare('SELECT custom_order_id, custom_title, custom_desc, custom_price, order_date, custom_delivery_time, status, client_id, service_id
                             FROM CustomOrders
                             WHERE custom_order_id = ?');

        $stmt->execute([$id]);
        $order = $stmt->fetch();
        
        return new CustomOrders(
            (int)$order['custom_order_id'],
            (string)$order['custom_title'],
            (string)$order['custom_desc'],
            (float)$order['custom_price'],
            (string)$order['order_date'],
            (int)$order['custom_delivery_time'],
            (string)$order['status'],
            (int)$order['client_id'],
            (int)$order['service_id']
        );
    }

    public static function getCustomOrdersFromService(PDO $db, int $service_id): array {
        $stmt = $db->prepare('SELECT custom_order_id, custom_title, custom_desc, custom_price, order_date, custom_delivery_time, status, client_id, service_id
                                FROM CustomOrders
                                WHERE service_id = ?
                                ORDER BY order_date DESC');
        $stmt->execute([$service_id]);

        $orders = array();
        while ($order = $stmt->fetch()) {
            $orders[] = new CustomOrders(
                (int)$order['custom_order_id'],
                (string)$order['custom_title'],
                (string)$order['custom_desc'],
                (float)$order['custom_price'],
                (string)$order['order_date'],
                (int)$order['custom_delivery_time'],
                (string)$order['status'],
                (int)$order['client_id'],
                (int)$order['service_id']
            );
        }
        return $orders;
    }

    public static function getCustomOrdersFromClient(PDO $db, int $client_id): array {
        $stmt = $db->prepare('SELECT custom_order_id, custom_title, custom_desc, custom_price, order_date,custom_delivery_time, status, client_id, service_id
                                FROM CustomOrders
                                WHERE client_id = ?
                                ORDER BY order_date DESC');
        $stmt->execute([$client_id]);

        $orders = array();
        while ($order = $stmt->fetch()) {
            $orders[] = new CustomOrders(
                (int)$order['custom_order_id'],
                (string)$order['custom_title'],
                (string)$order['custom_desc'],
                (float)$order['custom_price'],
                (string)$order['order_date'],
                (int)$order['custom_delivery_time'],
                (string)$order['status'],
                (int)$order['client_id'],
                (int)$order['service_id']
            );
        }
        return $orders;
    }

    public static function createCustomOrder(PDO $db, int $client_id, int $service_id, string $custom_title, string $custom_desc, float $custom_price, int $custom_delivery_time): int {
        $stmt = $db->prepare('INSERT INTO CustomOrders (status, custom_title, custom_desc, custom_price, order_date, custom_delivery_time, client_id, service_id) 
                            VALUES (?, ?, ?, ?, datetime("now"), ?, ?, ?)');
        $status = "pending";
        $stmt->execute([$status, $custom_title, $custom_desc, $custom_price, $custom_delivery_time, $client_id, $service_id]);
        return (int)$db->lastInsertId();
    }
}
?>
