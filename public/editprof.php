<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/user_class.php');

require_once(__DIR__ . '/../bd/connection_db.php');
$db= getDatabaseConnection();

require_once(__DIR__ . '/../templates/common.php');
require_once(__DIR__ . '/../templates/profile.php');

if(!$session->isLoggedIn()) die(header('Location: /'));

$user_id= $session->getUser_id();
$user= User::getUser($db, $user_id);

$scripts = ['profile'];

drawHeader($session, $scripts);
drawEditProfile($session);
drawFooter();

?>
