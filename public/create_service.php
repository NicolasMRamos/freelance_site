<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();
require_once(__DIR__ . '/../util/service_class.php');
require_once(__DIR__ . '/../util/categories_class.php');

require_once(__DIR__ . '/../templates/index.php');
require_once(__DIR__ . '/../templates/common.php');
require_once(__DIR__ . '/../templates/service.php');

require_once(__DIR__ . '/../bd/connection_db.php');

$db = getDatabaseConnection();
$categories = Category::getAll($db);

$scripts = ['service'];

drawHeader($session, $scripts);
drawServiceForm($session, $categories);
drawFooter();

?>
