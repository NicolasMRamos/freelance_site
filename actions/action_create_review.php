<?php
declare(strict_types=1);

require_once (__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once (__DIR__ . '/../util/reviews_class.php');
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
        header('Location: ' . ($status === 'Success' ? $redirectUrl : '/service.php'));
    }
    exit();
}

$user_id = $session->getUser_id();
if ($user_id === null) { respond('Error', 'Please log in to leave a review', '/login.php');}

$service_id    = filter_input(
    INPUT_POST, 'service_id',
    FILTER_VALIDATE_INT,
    ['options'=>['min_range'=>1]]
);
$rating        = filter_input(
    INPUT_POST, 'rating',
    FILTER_VALIDATE_INT,
    ['options'=>['min_range'=>1, 'max_range'=>5]]
);

$review_title  = trim((string)($_POST['review_title'] ?? ''));
$review_text   = trim((string)($_POST['review_text']  ?? ''));

$errors = [];
if ($service_id === false || $service_id === null) { $errors[] = 'Invalid service';}
if ($rating === false || $rating === null) { $errors[] = 'Star rating must be between 1 and 5';}
if ($review_title === '') { $errors[] = 'Review title is required';}
if ($review_text === '') { $errors[] = 'Review description is required';}

if (!empty($errors)) {
    respond('Error', implode("\n", $errors), "/service.php?id={$service_id}");
}

Reviews::createReview($db,$rating,$review_title,$review_text,$user_id,(int)$service_id);
$newReviewId = (int)$db->lastInsertId();

if ($newReviewId) {respond( 'Success',
                            'Review submitted',
                            "/service.php?id={$service_id}");
} else {respond('Error',
                'Failed to submit review',
                "/service.php?id={$service_id}");
}
