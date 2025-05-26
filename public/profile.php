<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/user_class.php');
require_once(__DIR__ . '/../util/service_class.php');
require_once(__DIR__ . '/../util/orders_class.php');
require_once(__DIR__ . '/../util/custom_orders_class.php');

require_once(__DIR__ . '/../bd/connection_db.php');
$db= getDatabaseConnection();

require_once(__DIR__ . '/../templates/common.php');
require_once(__DIR__ . '/../templates/profile.php');
require_once(__DIR__ . '/../templates/service.php');
require_once(__DIR__ . '/../templates/order.php');

if(!$session->isLoggedIn()) die(header('Location: /'));

$user_id= $session->getUser_id();
$user= User::getUser($db, $user_id);

$scripts = ['admin'];

if ($user instanceof Admin) {
    drawHeader($session, $scripts);
} else {
    drawHeader($session);
}
drawProfileInfo($user);

if($user->is_admin){
    drawAdminCentral($session);
}

if($user->is_fl){
    $services = Services::getServicesFromFreelancer($db, $user_id);
    drawFreelancerServices($db, $services, $session);
}

if($user->is_cl){
    $orders = Orders::getOrdersFromClient($db, $user_id);
    $corders = CustomOrders::getCustomOrdersFromClient($db, $user_id);
    drawClientOrders($db, $orders, $corders, $session);
}

drawFooter();

?>
