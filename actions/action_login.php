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
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/login.php'));
    }
    exit();
}

// Code
$username = trim($_POST['username']);
$password = $_POST['password'];

$user = User::getUserWithUsername($db, $username);

if($username === '' || $password === ''){
  respond('Error', 'Username and password required');
}

if(!$user){
  respond('Error', 'User doesn\'t exist');
}

if(!password_verify($password, $user->password)){
  respond('Error', 'Wrong password');
}

$session->setUser_id($user->user_id);
$session->setName($user->name);
respond('Success', 'Welcome back', '/index.php');

?>
