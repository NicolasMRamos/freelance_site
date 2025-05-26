<?php
declare(strict_types=1);
require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();

$allowed_actions = [
  'login' => __DIR__ . '/../actions/action_login.php',
  'register' => __DIR__ . '/../actions/action_register.php',
  'logout' => __DIR__ . '/../actions/action_logout.php',
  'edit_profile' => __DIR__ . '/../actions/action_edit_profile.php',
  'create_service' => __DIR__ . '/../actions/action_create_service.php',
  'create_review' => __DIR__ . '/../actions/action_create_review.php',
  'create_order' => __DIR__ . '/../actions/action_create_order.php',
  'create_corder' => __DIR__ . '/../actions/action_create_corder.php',
  'create_message' => __DIR__ . '/../actions/action_create_message.php',
  'admin' => __DIR__ . '/../actions/actions_admin.php'
];

$action = $_GET['action'] ?? null;

$actionsThatRequireCSRF = [
    'register',
    'login',
    'create_service',
    'create_review',
    'edit_profile',
    'admin',
    'create_order',
    'create_corder',
    'create_message'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, $actionsThatRequireCSRF)) {
    $csrf = $_POST['csrf'] ?? '';
    if (!$session->validateCSRFToken($csrf)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'Error', 'message' => 'Invalid CSRF token', 'redirect' => null]);
        exit();
    }
}

if (!isset($allowed_actions[$action])) {
  http_response_code(400);
  echo 'Invalid action.';
  exit();
}

require_once($allowed_actions[$action]);
?>