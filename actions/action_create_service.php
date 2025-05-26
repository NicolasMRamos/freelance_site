<?php

declare(strict_types=1);

require_once (__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once (__DIR__ . '/../util/service_class.php');
require_once (__DIR__ . '/../util/categories_class.php');

require_once (__DIR__ . '/../bd/connection_db.php');
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
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/create_service.php'));
    }
    exit();
}

// Code
$user_id = $session->getUser_id();
if ($user_id === null) {
    respond('Error', 'Please log in to create a service', '/login.php');
}

$title = trim((string)($_POST['service_title']));
$description= trim((string)($_POST['service_desc']));
$price = (float)$_POST['service_price'];
$delivery_time = (int)$_POST['service_delivery_time'];
$category = (string)($_POST['service_category']);

$errors = [];

if ($title === '') {$errors[] = 'Title is required';}

if ($description === '') { $errors[] = 'Description is required';}

if (!is_numeric($price) || (float)$price< 0) { $errors[] = 'Price must be a positive number';} 

if (filter_var($delivery_time, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]) === false) { $errors[] = 'Delivery time must be at least 1 day';} 

if ($category === '') { $errors[] = 'Please pick a category';}

if (!empty($errors)) {
    respond('Error', implode("\n", $errors));
}

Services::createService($db, $price, $delivery_time, $title, $description, $category, $session->getUser_id());
$newService_id = $db -> lastInsertId();

$newService = Services::getServiceFromId($db, (int)$newService_id);

if ($newService) {
    respond('Success', 'Service created successfully', "/service.php?id={$newService_id}");
} elseif (!$newService_id || !$newservice) {
    respond('Error', 'Service creation failed');
}

?>
