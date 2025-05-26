<?php

declare(strict_types = 1);

require_once(__DIR__ . '/../util/session_class.php');
$session = new Session();

$session->logout();

header('Location: /login.php');
exit();

?>