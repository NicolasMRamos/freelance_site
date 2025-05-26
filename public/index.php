<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/service_class.php');
require_once(__DIR__ . '/../util/user_class.php');
require_once(__DIR__ . '/../util/categories_class.php');

require_once(__DIR__ . '/../bd/connection_db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../templates/index.php');

$services = Services::getAllServices($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'filter') {

    if (!$session->validateCSRFToken($_POST['csrf'] ?? '')) {
        die('Invalid CSRF token');
    }

    $category = $_POST['service_category'] ?? null;
    $maxPrice = isset($_POST['price_filter']) ? (int)($_POST['max_price'] ?? 1000000) : null;
    $maxDelivery = isset($_POST['delivtime_filter']) ? (int)($_POST['max_delivtime'] ?? 10000) : null;
    $minRating = isset($_POST['rating_filter']) ? (float)($_POST['min_rating'] ?? 0) : null;

    $services = array_filter($services, function($service) use ($db, $category, $maxPrice, $maxDelivery, $minRating) {
        if ($category && $service->category !== $category)
            return false;
        if ($maxPrice !== null && $service->price > $maxPrice)
            return false;
        if ($maxDelivery !== null && $service->delivery_time > $maxDelivery)
            return false;
        if ($minRating !== null) {
            $rating = Reviews::getAverageRating($db, $service->service_id);
            if ($rating < $minRating)
                return false;
        }
        return true;
    });
}

$categories = Category::getAll($db);

$user = null;
if (($user_id = $session->getUser_id()) !== null) {
    $user = User::getUser($db, (int)$user_id);
}

drawHeaderIndex($session);
drawIndexPage($db, $session, $services, $user, $categories);
drawFooterIndex();

?>
