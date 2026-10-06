<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
require_once dirname(__DIR__) . '/app/page.php';

try {
    $user = current_user();
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    page_start('welcome');
    echo '<div class="form-content welcome-content"><span class="yellow-label">BACK SOON</span><h1>A little pause.</h1><p class="welcome-description">We couldn’t load your account. Please try again shortly.</p><a class="primary-button" href="welcome.php">Try again</a></div>';
    page_end();
    exit;
}
if (!$user) {
    unset($_SESSION['user_id']);
    redirect('login.php');
}

page_start('welcome');
?>
<div class="form-content welcome-content">
    <span class="yellow-label">YOU’RE RIGHT AT HOME <?= icon('paw') ?></span>
    <div class="welcome-check" aria-hidden="true"><?= icon('check') ?></div>
    <h1>Hey, <?= escape($user['name']) ?><span class="welcome-period">.</span></h1>
    <p class="welcome-description">You’re signed in and right where you belong.<br>It’s good to have you here.</p>
    <div class="account-summary">
        <span class="account-icon"><?= icon('user') ?></span>
        <div><span class="account-label">YOUR ACCOUNT</span><strong><?= escape($user['name']) ?></strong><span class="account-email"><?= escape($user['email']) ?></span></div>
        <span class="signed-in-dot" title="Signed in"></span>
    </div>
    <div class="notice notice-success" role="status"><?= icon('check') ?><p>You’ve logged in successfully. Make yourself at home.</p></div>
    <form method="post" action="logout.php">
        <input type="hidden" name="csrf_token" value="<?= escape(csrf_token()) ?>">
        <button type="submit" class="primary-button"><span>Log out</span><?= icon('arrow') ?></button>
    </form>
    <p class="welcome-footnote">Until next time. We’ll keep your spot warm.</p>
</div>
<?php page_end(); ?>

