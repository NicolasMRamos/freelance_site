<?php
declare(strict_types=1);

require_once (__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once (__DIR__ . '/../util/orders_class.php');
require_once (__DIR__ . '/../util/custom_orders_class.php');
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
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/create_order.php'));
    }
    exit();
}

$errors = [];

$session = new Session();
if (!$session->isLoggedIn() || $_POST['csrf'] !== $session->getCSRFToken()) {
    respond('Error','Invalid session or CSRF token.');
}

$client_id = $session->getUser_Id();
$service_id = (int)$_POST['service_id'] ?? null;

if ($service_id === null) {
    respond('Error', 'Missing service ID.');
}

$co_title = trim($_POST['co_title']);
$co_desc = trim($_POST['co_desc']);
$co_price = (float)$_POST['co_price'];
$co_deliv_tim = (int)$_POST['co_deliv_time'];

if($co_title == ""){
    $errors[] = 'Title is required';
}

if($co_desc == ""){
    $errors[] = 'Description is required';
}

if(!empty($errors)){
    respond('Error', implode("\n", $errors));
}

CustomOrders::createCustomOrder($db, $client_id, $service_id, $co_title, $co_desc, $co_price, $co_deliv_tim);
respond('Success', 'Custom order created succesfully');
?>
