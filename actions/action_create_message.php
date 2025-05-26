<?php
declare(strict_types=1);

require_once __DIR__ . '/../util/session_class.php';
$session = new Session();

require_once __DIR__ . '/../util/messages_class.php';

require_once __DIR__ . '/../bd/connection_db.php';
$db = getDatabaseConnection();

require_once __DIR__ . '/../util/ajax_func.php';

function respond(string $status, string $message, ?string $redirectUrl = null): void {
    global $session;
    if (is_ajax()) {
        header('Content-Type: application/json');
        echo json_encode([
            'status'   => $status,
            'message'  => $message,
            'redirect' => $redirectUrl
        ]);
    } else {
        $session->addMessage($status, $message);
        $url = $redirectUrl ?? '/';
        header("Location: {$url}");
    }
    exit();
}

$client_id = $session->getUser_id();
if ($client_id === null) {
    respond('Error', 'Please log in to send a message', '/login.php');
}

$service_id    = filter_input(
    INPUT_POST, 'service_id',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);
$message_title = trim((string)($_POST['message_title'] ?? ''));
$message_text  = trim((string)($_POST['message_text']  ?? ''));

$errors = [];
if ($service_id === false || $service_id === null) {
    $errors[] = 'Invalid service.';
}
if ($message_title === '') {
    $errors[] = 'Subject is required';
}
if ($message_text === '') {
    $errors[] = 'Message body is required';
}

if (!empty($errors)) {
    respond('Error', implode("\n", $errors), "/service.php?id={$service_id}");
}


Messages::createMessage($db,$message_title,$message_text,$client_id,(int)$service_id);
$newMessageId = (int)$db->lastInsertId();

if ($newMessageId) {
    respond(
        'Success',
        'Message sent!',
        "/service.php?id={$service_id}"
    );
} else {
    respond(
        'Error',
        'Failed to send message.',
        "/service.php?id={$service_id}"
    );
}
