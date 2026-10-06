<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Please use the Log out button to sign out.');
}
if (!valid_csrf()) {
    http_response_code(403);
    exit('This form has expired. Return to your account and try again.');
}

$_SESSION = [];
session_regenerate_id(true);
$_SESSION['success'] = 'You’ve logged out. See you again soon!';
redirect('login.php');

