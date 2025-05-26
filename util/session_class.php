<?php
  class Session {

    private array $messages;

    // CSRF token related
    public function generateRandomToken() {
      return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public function validateCSRFToken(string $token): bool {
      return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function getCSRFToken(): string {
      return $_SESSION['csrf_token'];
    }

    // Session class related
    public function __construct() {
      if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
          'lifetime' => 0,
          'path'     => '/',
          'domain'   => '',         // empty means localhost
          'secure'   => false,      // set to true only if using HTTPS (not localhost)
          'httponly' => true        // prevent access from JS
        ]);
        session_start();
      }

      if(!isset($_SESSION['csrf_token'])){
        $_SESSION['csrf_token'] = $this->generateRandomToken();
      }
      $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);
    }

    public function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
        return $this->messages;
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['user_id']);    
    }

    public function logout() {
      session_destroy();
    }

    public function getUser_id() : ?int {
      return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;    
    }

    public function getName() : ?string {
      return isset($_SESSION['name']) ? $_SESSION['name'] : null;
    }

    public function setUser_id(int $user_id) {
      $_SESSION['user_id'] = $user_id;
    }

    public function setName(string $name) {
      $_SESSION['name'] = $name;
    }

  }
?>