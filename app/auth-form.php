<?php $isRegister = basename($_SERVER['SCRIPT_NAME']) === 'register.php'; ?>
<div class="form-content">
    <nav class="auth-tabs" aria-label="Account navigation">
        <a href="login.php" <?= !$isRegister ? 'aria-current="page"' : '' ?>>Log in</a>
        <a href="register.php" <?= $isRegister ? 'aria-current="page"' : '' ?>>Create an account</a>
    </nav>
    <div class="form-heading">
        <span class="yellow-label"><?= $isRegister ? 'THERE’S ROOM FOR YOU' : 'HELLO AGAIN' ?> <?= icon('paw') ?></span>
        <h1><?= $isRegister ? 'Join the pack.' : 'Welcome back.' ?></h1>
        <p><?= $isRegister ? 'A fresh start is just a few details away.' : 'Your little corner of the world is waiting.' ?></p>
    </div>

    <?php if ($success): ?>
        <div class="notice notice-success" role="status"><?= icon('check') ?><p><?= escape($success) ?></p></div>
    <?php endif; ?>
    <?php if (isset($errors['form'])): ?>
        <div class="notice notice-error" role="alert"><?= icon('info') ?><p><?= escape($errors['form']) ?></p></div>
    <?php endif; ?>

    <form class="auth-form" method="post" action="<?= $isRegister ? 'register.php' : 'login.php' ?>" data-auth-form="<?= $isRegister ? 'register' : 'login' ?>" novalidate>
        <input type="hidden" name="csrf_token" value="<?= escape(csrf_token()) ?>">
        <?php if ($isRegister): ?>
            <?php field('name', 'Full name', 'text', 'What should we call you?', 'name', $errors, $values, 'user'); ?>
        <?php endif; ?>
        <?php field('email', 'Email address', 'email', 'you@example.com', 'email', $errors, $values, 'mail'); ?>
        <?php field('password', 'Password', 'password', $isRegister ? 'Make it a good one' : 'Enter your password', $isRegister ? 'new-password' : 'current-password', $errors, $values, 'lock'); ?>

        <?php if ($isRegister): ?>
            <div class="password-guidance" id="password-guidance">
                <span data-password-rule="length"><span class="rule-dot"></span>8+ characters</span>
                <span data-password-rule="case"><span class="rule-dot"></span>Aa</span>
                <span data-password-rule="number"><span class="rule-dot"></span>A number</span>
            </div>
            <?php field('confirm_password', 'Confirm password', 'password', 'One more time, just to be sure', 'new-password', $errors, $values, 'lock'); ?>
        <?php else: ?>
            <div class="form-options">
                <label class="checkbox-label"><input type="checkbox" id="remember-email"><span>Remember my email</span></label>
                <span class="password-note">Your password stays private.</span>
            </div>
        <?php endif; ?>

        <button class="primary-button" type="submit"><span><?= $isRegister ? 'Create my account' : 'Log in' ?></span><?= icon('arrow') ?><span class="button-spinner" aria-hidden="true"></span></button>
        <p class="form-switch"><?= $isRegister ? 'Already have an account?' : 'Not part of the pack yet?' ?> <a href="<?= $isRegister ? 'login.php' : 'register.php' ?>"><?= $isRegister ? 'Log in' : 'Sign up' ?></a></p>
    </form>
    <div class="trust-note"><?= icon('shield') ?><span>Your details are safe. Your company is welcome.</span></div>
</div>

