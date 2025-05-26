<?php
declare(strict_types=1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/user_class.php');
require_once(__DIR__ . '/../util/admin_class.php');

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
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/profile.php'));
    }
    exit();
}

// Code
$user = User::getUser($db, $session->getUser_id());

if (!($user instanceof Admin)) {
    respond('Error', 'Forbidden', '/login.php');
}

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? null;

try {
    switch ($action) {
    case 'promote_user':
        if ($id) { // This represents the username, not an id. Handled in 'admin.js'
            $target = User::getUserWithUsername($db, $id);
            if (!$target) throw new Exception("User '$id' does not exist");
            $user->promoteUser($db, $target->user_id);
        }
        break;

    case 'demote_user':
        if ($id) {
            $target = User::getUserWithUsername($db, $id);
            if (!$target) throw new Exception("User '$id' does not exist");
            $user->demoteUser($db, $target->user_id);
        }
        break;

    case 'delete_user':
        if ($id) {
            $target = User::getUserWithUsername($db, $id);
            if (!$target) throw new Exception("User '$id' does not exist");
            $user->deleteUser($db, $target->user_id);
        }
        break;

    case 'delete_service':
        if ($id) $user->deleteService($db, (int)$id);
        break;

    case 'delete_review':
        if ($id) $user->deleteReview($db, (int)$id);
        break;

    case 'delete_message':
        if ($id) $user->deleteMessage($db, (int)$id);
        break;

    case 'delete_order':
        if ($id) $user->deleteOrder($db, (int)$id);
        break;

    case 'delete_custom_order':
        if ($id) $user->deleteCustomOrder($db, (int)$id);
        break;

    case 'add_category':
        if ($name) $user->addCategory($db, $name);
        break;

    case 'delete_category':
        if ($id) $user->deleteCategory($db, (int)$id);
        break;

    default:
        respond('Error', 'Unknown admin action');
    }
    respond('Success', 'Action succesful');
} catch (Exception $e) {
    respond('Error', $e->getMessage());
}

