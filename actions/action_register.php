<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/user_class.php');

require_once(__DIR__ . '/../bd/connection_db.php');
$db = getDatabaseConnection();

require_once (__DIR__ . '/../util/ajax_func.php');

// Ajax related
function respond(string $status, string $message, ?string $redirectUrl = null): void {
    global $session;
    if (is_ajax()) {
        header('Content-Type: application/json');
        echo json_encode(['status'   => $status,'message'  => $message, 'redirect' => $redirectUrl]);
    } else {
        $session->addMessage($status, $message);
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/register.php'));
    }
    exit();
}

// Code
$name = trim($_POST['name']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$password2 = $_POST['confirm_password'];

$errors = [];

if ($name === '' || $username === '' || $email === '' || $password === '' || $password2 === '') {
  $errors[] = 'All fields are required';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'Invalid email format';
}

$user = User::getUserWithUsername($db, $username);

if($user) {
  $errors[] = 'User with this username already exists';
}

if (strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters long';
}

if (!preg_match('/[a-z]/', $password)) {
    $errors[] = 'Password must include at least one lowercase letter';
}

if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = 'Password must include at least one uppercase letter';
}

if (!preg_match('/[0-9]/', $password)) {
    $errors[] = 'Password must include at least one number';
}

if (!preg_match('/[\W_]/', $password)) {
    $errors[] = 'Password must include at least one special character';
}

if($password != $password2 ){
  $errors[] = 'Passwords don\'t match';
}

if(User::emailExists($db, $email)){
  $errors[] = 'User with this email already exists';
}

if (!empty($errors)) {
  respond('Error', implode("\n", $errors));
}

$is_fl = isset($_POST['is_fl']) ? 1 : 0;
$is_cl = isset($_POST['is_cl']) ? 1 : 0;

User::save_newUser($db, $name, $username, $password, $email, $is_fl, $is_cl);
$user_id = $db -> lastInsertId();

$newUser = User:: getUser($db, (int)$user_id);

if ($newUser) {
  $session->setUser_id($newUser->user_id);
  $session->setName($newUser->name);
  respond('Success', 'Account created', '/index.php');
}

?>