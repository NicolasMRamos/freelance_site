<?php
  declare(strict_types = 1);

  class Services {
    public int $service_id;
    public float $price;
    public int $delivery_time;
    public string $service_title;
    public string $service_desc;
    public bool $active;
    public string $category;
    public int $freelancer_id;

    public function __construct(int $service_id, float $price, int $delivery_time, string $service_title, string $service_desc, bool $active, string $category, int $freelancer_id) {
        $this->service_id = $service_id;
        $this->price = $price;
        $this->delivery_time = $delivery_time;
        $this->service_title = $service_title;
        $this->service_desc = $service_desc;
        $this->active = $active;
        $this->category = $category;
        $this->freelancer_id = $freelancer_id;
    }

    public static function getAllServices(PDO $db): array {
        $stmt = $db->prepare('SELECT service_id, price, delivery_time, service_title, service_desc, active, category, freelancer_id
                              FROM Services');

        $stmt->execute();

        $services = array();
        while ($service = $stmt->fetch()) {
            $services[] = new Services(
                (int)$service['service_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                (string)$service['service_title'],
                (string)$service['service_desc'],
                (bool)$service['active'],
                (string)$service['category'],
                (int)$service['freelancer_id']
            );
        }
        return $services;
    }

    public static function getServicesFromFreelancer(PDO $db, int $freelancer_id): array {
        $stmt = $db->prepare('SELECT service_id, price, delivery_time, service_title, service_desc, active, category, freelancer_id
                              FROM Services
                              WHERE freelancer_id = ?');
        $stmt->execute([$freelancer_id]);

        $services = array();
        while ($service = $stmt->fetch()) {
            $services[] = new Services(
                (int)$service['service_id'],
                (float)$service['price'],
                (int)$service['delivery_time'],
                (string)$service['service_title'],
                (string)$service['service_desc'],
                (bool)$service['active'],
                (string)$service['category'],
                (int)$service['freelancer_id']
            );
        }
        return $services;
    }

    public static function getServiceFromId(PDO $db, int $service_id): Services {
        $stmt = $db->prepare('SELECT service_id, price, delivery_time, service_title, service_desc, active, category, freelancer_id
                              FROM Services
                              WHERE service_id = ?');

        $stmt->execute([$service_id]);
        $service = $stmt->fetch();

        return new Services(
            (int)$service['service_id'],
            (float)$service['price'],
            (int)$service['delivery_time'],
            (string)$service['service_title'],
            (string)$service['service_desc'],
            (bool)$service['active'],
            (string)$service['category'],
            (int)$service['freelancer_id']
          );
    }

    public static function createService(PDO $db, float $price,int $delivery_time, string $service_title, string $service_desc, string $category, int $freelancer_id) {
        $stmt = $db->prepare(' INSERT INTO Services (price, delivery_time, service_title, service_desc, active, category, freelancer_id)
                                VALUES (?, ?, ?, ?, 1, ?, ?)');
        $stmt->execute([
          $price,
          $delivery_time,
          $service_title,
          $service_desc,
          $category,
          $freelancer_id
        ]);
      }

    public function getFreelancerName(PDO $db): string {
        $stmt = $db->prepare('SELECT username FROM Users WHERE user_id = ?');
        $stmt->execute([$this->freelancer_id]);

        $username = $stmt->fetch();
        return (string)$username['username'];
     }


    }
  
?>