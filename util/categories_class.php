<?php
declare(strict_types=1);

class Category {
    public string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    public static function getAll(PDO $db): array {
        $stmt = $db->query('SELECT name FROM Categories');
        
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getByName(PDO $db, string $name): ?Category {
        $stmt = $db->prepare('SELECT name FROM Categories WHERE name = ?');
        $stmt->execute([$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (! $row) {
            return null;
        }

        return new Category($row['name']);
    }

    
    public static function create(PDO $db, string $name): void {
        $stmt = $db->prepare('INSERT INTO Categories(name) VALUES (?)');

        $stmt->execute([$name]);
    }

    public function delete(PDO $db): void {
        $stmt = $db->prepare('DELETE FROM Categories WHERE name = ?');

        $stmt->execute([$this->name]);
    }

    public function rename(PDO $db, string $newName): void {
        $stmt = $db->prepare('UPDATE Categories SET name = ? WHERE name = ?');
        $stmt->execute([$newName, $this->name]);

        $this->name = $newName;
    }
}
