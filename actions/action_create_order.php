<?php
declare(strict_types=1);

require_once (__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once (__DIR__ . '/../util/orders_class.php');
require_once (__DIR__ . '/../util/ajax_func.php');

require_once (__DIR__ . '/../bd/connection_db.php');
$db = getDatabaseConnection();


function respond(string $status, string $message, ?string $redirectUrl = null): void {
    global $session;
    if (is_ajax()) {
        header('Content-Type: application/json');
        echo json_encode(['status'   => $status,'message'  => $message, 'redirect' => $redirectUrl]);
    } else {
        $session->addMessage($status, $message);
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/profile.php#cl_track'));
    }
    exit();
}

$session = new Session();
if (!$session->isLoggedIn() || $_POST['csrf'] !== $session->getCSRFToken()) {
    respond('Error','Invalid session or CSRF token.');
}

$client_id = $session->getUser_Id();
$service_id = $_POST['service_id'] ?? null;

if ($service_id === null) {
    respond('Error', 'Missing service ID.');
}

Orders::createOrder($db, (int)$client_id, (int)$service_id);
respond('Success','Order created succesfully');
?>
