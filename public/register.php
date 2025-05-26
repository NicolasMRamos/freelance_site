<?php
declare(strict_types =1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/user_class.php');

require_once(__DIR__ . '/../bd/connection_db.php');
$db= getDatabaseConnection();

require_once(__DIR__ . '/../templates/common.php');
require_once(__DIR__ . '/../templates/register.php');

$scripts = ['auth'];

drawHeader($session, $scripts);
drawRegisterPage($session);
drawFooter();

?>
