<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
redirect(empty($_SESSION['user_id']) ? 'login.php' : 'welcome.php');

