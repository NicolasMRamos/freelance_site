<?php
  declare(strict_types = 1);
  require_once(__DIR__ . '/../util/admin_class.php');

  class User {
    public int $user_id;
    public string $name;
    public string $username;
    public string $password;
    public string $email;
    public string $register_date;
    public int $is_fl;
    public int $is_cl;
    public int $is_admin;

    public function __construct(int $user_id, string $name, string $username, string $password, string $email, string $register_date, int $is_fl, int $is_cl, int $is_admin)
    {
      $this->user_id = $user_id;
      $this->name = $name;
      $this->username = $username;
      $this->password = $password;
      $this->email = $email;
      $this->register_date = $register_date;
      $this->is_fl = $is_fl;
      $this->is_cl = $is_cl;
      $this->is_admin = $is_admin;
    }

    static function save_newUser(PDO $db, $name, $username, $password, $email, $is_fl, $is_cl, $is_admin=0 ) {

      $options = ['cost'=>12];
      $stmt = $db->prepare('INSERT INTO Users (name, username, password, email, is_fl, is_cl, is_admin)
                            VALUES (?,?,?,?,?,?,?)');

      $stmt->execute(array($name, $username, password_hash($password, PASSWORD_DEFAULT, $options), strtolower($email), $is_fl, $is_cl,$is_admin));

    }


    static function getUserWithPassword(PDO $db, string $username, string $password) : ?User {
      $stmt = $db->prepare('SELECT user_id, name, username, password, email, register_date, is_fl, is_cl, is_admin 
                            FROM Users
                            WHERE username = ?');

      $stmt->execute(array($username));

      $user = $stmt->fetch();
  
      if ($user && password_verify($password, $user['password'])){
        return new User(
            (int)$user['user_id'],
            (string)$user['name'],
            (string)$user['username'],
            (string)$user['password'],
            (string)$user['email'],
            (string)$user['register_date'],
            (int)$user['is_fl'],
            (int)$user['is_cl'],
            (int)$user['is_admin']
        );
      } else return null;
    }

    static function getUserWithUsername(PDO $db, string $username): ?User {
      $stmt = $db->prepare('SELECT user_id, name, username, password, email, register_date, is_fl, is_cl, is_admin 
                            FROM Users
                            WHERE lower(username) = lower(?)');
      $stmt->execute([$username]);
      $user = $stmt->fetch();

      if (!$user) return null;

      if((int)$user['is_admin'] === 1) {
        return new Admin(
          (int)$user['user_id'],
          (string)$user['name'],
          (string)$user['username'],
          (string)$user['password'],
          (string)$user['email'],
          (string)$user['register_date'],
          (int)$user['is_fl'],
          (int)$user['is_cl'],
          1
        );
      }

      return new User(
        (int)$user['user_id'],
        (string)$user['name'],
        (string)$user['username'],
        (string)$user['password'],
        (string)$user['email'],
        (string)$user['register_date'],
        (int)$user['is_fl'],
        (int)$user['is_cl'],
        0
      );
    }

    static function getUser(PDO $db, int $user_id) : ?User {
      $stmt = $db->prepare('SELECT user_id, name, username, password, email, register_date, is_fl, is_cl, is_admin                           
                            FROM Users 
                            WHERE user_id = ?');

      $stmt->execute(array($user_id));
      $user = $stmt->fetch();

      if (!$user) return null;
      
      if ((int)$user['is_admin'] === 1) {
        return new Admin(
          (int)$user['user_id'],
          (string)$user['name'],
          (string)$user['username'],
          (string)$user['password'],
          (string)$user['email'],
          (string)$user['register_date'],
          (int)$user['is_fl'],
          (int)$user['is_cl'],
          1
        );
      }

      return new User(
        (int)$user['user_id'],
        (string)$user['name'],
        (string)$user['username'],
        (string)$user['password'],
        (string)$user['email'],
        (string)$user['register_date'],
        (int)$user['is_fl'],
        (int)$user['is_cl'],
        0
      );
    }

    function save_NewPassword($db, $newpassword) {
      $options = ['cost'=>12];
      $stmt = $db->prepare('UPDATE Users SET password = ?
                            WHERE user_id = ?');

      $stmt->execute(array(password_hash($newpassword, PASSWORD_DEFAULT, $options), $this->user_id));
    }

    function save_editUser($db) {
      $stmt = $db->prepare('UPDATE Users SET email = ?, name = ?, username = ? WHERE user_id = ?');
      $stmt->execute(array($this->email, $this->name, $this->username, $this->user_id));
    }

    public function isFreelancer(): bool {
      return (bool)$this->is_fl;
    }

    static function emailExists(PDO $db, string $email): bool {
      $stmt = $db->prepare('SELECT COUNT(*) FROM Users WHERE lower(email) = lower(?)');
      $stmt->execute([strtolower($email)]);
      return $stmt->fetchColumn() > 0;
    }

    static function usernameExists(PDO $db, string $username): bool {
      $stmt = $db->prepare('SELECT COUNT(*) FROM Users WHERE (username) = (?)');
      $stmt->execute([$username]);
      return $stmt->fetchColumn() > 0;
    }

  }
?>