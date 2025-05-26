<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/user_class.php');

require_once(__DIR__ . '/../bd/connection_db.php');
$db = getDatabaseConnection();

require_once (__DIR__ . '/../util/ajax_func.php');

// Ajax related
function respond($status, $message, ?string $redirectUrl = null) {
    if (is_ajax()) {
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'message' => $message, 'redirect' => $redirectUrl]);
    } else {
        global $session;
        $session->addMessage($status, $message);
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/edit_profile.php'));
    }
    exit();
}

// Code
$user_id = $session->getUser_id();
if ($user_id === null) {
    respond('Error', 'You must be logged in to edit your profile', '/login.php');
}
$user = User::getUser($db, $user_id);

$email_new = trim($_POST['email']);
$name_new = trim($_POST['name']);
$username_new = trim($_POST['username']);
$password_new = $_POST['password'];
$password2_new = $_POST['confirm_password'];
$current_password = $_POST['current_password'];

if (!empty($email_new) || !empty($name_new) || !empty($username_new) || !empty($password_new)) {
    if (!password_verify($current_password, $user->password)) {
        respond('Error', 'Current password is incorrect');
    }

    if (!empty($email_new)) {
        if (!filter_var($email_new, FILTER_VALIDATE_EMAIL)) {
            respond('Error', 'Invalid email format');
        }
        if (strtolower($email_new) !== strtolower($user->email) && User::emailExists($db, $email_new)) {
            respond('Error', 'This email is already in use');
        }
        $user->email = strtolower($email_new);
    }

    if (!empty($name_new)) {
        $user->name = $name_new;
    }

    if (!empty($username_new)) {
        if ($username_new !== $user->username && User::usernameExists($db, $username_new)) {
            respond('Error', 'This username is already in use');
        }
        $user->username = $username_new;
    }

    if (!empty($password_new)) {
        if ($password_new !== $password2_new) {
            respond('Error', 'Passwords do not match');
        }
        $user->save_NewPassword($db, $password_new);
    }

    $user->save_editUser($db);
    respond('Success', 'Account edited', '/profile.php');
} else {
    respond('Error', 'No changes submitted');
}

?>
