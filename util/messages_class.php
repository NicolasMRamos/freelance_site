<?php
declare(strict_types=1);

class Messages {
    public int $message_id;
    public string $message_title;
    public string $message_text;
    public string $message_date;
    public int $client_id;
    public int $service_id;

    public function __construct(int $message_id, string $message_title, string $message_text, string $message_date, int $client_id, int $service_id) 
    {
        $this->message_id = $message_id;
        $this->message_title = $message_title;
        $this->message_text = $message_text;
        $this->message_date = $message_date;
        $this->client_id = $client_id;
        $this->service_id = $service_id;
    }


    public static function getMessageFromId(PDO $db, int $id): Messages {
        $stmt = $db->prepare('SELECT message_id, message_title, message_text, message_date, client_id, service_id
                              FROM Messages
                              WHERE message_id = ?');

        $stmt->execute([$id]);
        $message = $stmt->fetch();
       
        return new Messages(
            (int)$message['message_id'],
            (string)$message['message_title'],
            (string)$message['message_text'],
            (string)$message['message_date'],
            (int)$message['client_id'],
            (int)$message['service_id']
        );
    }

    public static function getMessagesFromClient(PDO $db, int $client_id): array {
        $stmt = $db->prepare('SELECT message_id, message_title, message_text, message_date, client_id, service_id
                             FROM Messages
                             WHERE client_id = ?
                             ORDER BY message_date DESC');
        $stmt->execute([$client_id]);

        $messages = array();
        while ($message = $stmt->fetch()) {
            $messages[] = new Messages(
                (int)$message['message_id'],
                (string)$message['message_title'],
                (string)$message['message_text'],
                (string)$message['message_date'],
                (int)$message['client_id'],
                (int)$message['service_id']
            );
        }
        return $messages;
    }

    public static function getMessagesFromService(PDO $db, int $service_id): array {
        $stmt = $db->prepare(
            'SELECT message_id, message_title, message_text, message_date, client_id, service_id
               FROM Messages
              WHERE service_id = ?
           ORDER BY message_date DESC'
        );
        $stmt->execute([$service_id]);

        $messages = [];
        while ($message = $stmt->fetch()) {
            $messages[] = new Messages(
                (int)$message['message_id'],
                (string)$message['message_title'],
                (string)$message['message_text'],
                (string)$message['message_date'],
                (int)$message['client_id'],
                (int)$message['service_id']
            );
        }
        return $messages;
    }

    public function getClientUsername(PDO $db): string {
    $stmt = $db->prepare('SELECT username FROM Users WHERE user_id = ?');
    $stmt->execute([$this->client_id]);

    $username = $stmt->fetchColumn();

    return $username !== false ? (string)$username : '';
    }

  public static function createMessage(PDO $db,string $message_title, string $message_text,int $client_id, int $service_id): void {
    $stmt = $db->prepare('INSERT INTO Messages (message_title, message_text, client_id, service_id)
                            VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $message_title,
        $message_text,
        $client_id,
        $service_id
    ]);
    }

}
?>
