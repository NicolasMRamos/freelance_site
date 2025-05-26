<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/service_class.php');
require_once(__DIR__ . '/../util/user_class.php');
require_once(__DIR__ . '/../util/messages_class.php');

require_once(__DIR__ . '/../bd/connection_db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../templates/common.php');
require_once(__DIR__ . '/../templates/service.php');
require_once(__DIR__ . '/../templates/review.php');
require_once(__DIR__ . '/../templates/order.php');
require_once(__DIR__ . '/../templates/message.php');

$service_id = (int)$_GET['id'] ?? null;
$service = Services::getServiceFromID($db, $service_id);
if (!$service) {
    die(header('Location: /'));
}
$reviews = Reviews::getReviewsFromService($db, $service->service_id);
$average  = Reviews::getAverageRating($db, $service->service_id);
$messages = Messages::getMessagesFromService($db, $service->service_id);

$scripts = ['review','order','message'];

drawHeader($session, $scripts);
drawService($session, $service, $db, $average);

$user_id= $session->getUser_id();
if($user_id) {
    $user= User::getUser($db, $user_id);
    if($user->is_cl){
        drawOrderForm($session, $service_id);
    }
}

drawMessages($db, $messages);
drawMessageForm($session, $service->service_id);
drawReviewForm($session, $service->service_id);
drawReviews($db, $session,$reviews);
drawFooter();

?>
