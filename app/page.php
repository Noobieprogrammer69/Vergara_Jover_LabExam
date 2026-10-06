<?php
declare(strict_types=1);
require_once __DIR__ . '/icons.php';

function page_start(string $mode): void
{
    $registration = $mode === 'register';
    $welcome = $mode === 'welcome';
    $title = $welcome ? 'Your little corner' : ($registration ? 'Create an account' : 'Welcome back');
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="theme-color" content="#10284b">
        <meta name="description" content="A happy little corner of the internet. Create your Pawspace account or sign in.">
        <title><?= $title ?> · Pawspace</title>
        <link rel="icon" href="assets/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="assets/style.css">
        <script src="assets/app.js" defer></script>
    </head>
    <body class="page-<?= escape($mode) ?>">
        <div class="page-wrap">
            <header class="site-header">
                <a class="brand" href="index.php" aria-label="Pawspace home">
                    <span class="brand-icon"><?= icon('paw') ?></span>
                    <span>pawspace<span class="brand-dot">.</span></span>
                </a>
                <?php if (!$welcome): ?>
                    <div class="header-navigation">
                        <span><?= $registration ? 'Already part of the pack?' : 'New around here?' ?></span>
                        <a href="<?= $registration ? 'login.php' : 'register.php' ?>"><?= $registration ? 'Log in' : 'Join the pack' ?> <?= icon('arrow-up') ?></a>
                    </div>
                <?php else: ?>
                    <span class="header-note">Your happy place, found.</span>
                <?php endif; ?>
            </header>
            <main class="auth-shell">
                <section class="story-panel" aria-label="Welcome to Pawspace">
                    <div class="story-eyebrow"><span class="little-spark"></span> GOOD COMPANY STARTS HERE</div>
                    <div class="portrait-scene">
                        <div class="orbit orbit-one" aria-hidden="true"></div>
                        <div class="orbit orbit-two" aria-hidden="true"></div>
                        <svg class="doodle doodle-star" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m24 3 3 16 18 5-18 4-3 17-4-17-17-4 17-5 4-16Z"/></svg>
                        <div class="portrait-frame"><img src="assets/images/puppy.png" width="1086" height="1448" alt="A fluffy golden retriever puppy sitting in a sunlit garden" fetchpriority="high"><span class="photo-caption">a little dose of happy.</span></div>
                        <span class="portrait-sticker paw-sticker" aria-hidden="true"><?= icon('paw') ?></span>
                        <span class="portrait-sticker heart-sticker" aria-hidden="true"><?= icon('heart') ?></span>
                        <svg class="doodle doodle-trail" viewBox="0 0 85 58" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M3 8c25 0 28 22 21 26S8 21 25 13s36 7 41 35m-12-6 13 9 7-15"/></svg>
                    </div>
                    <div class="story-copy">
                        <h2>Life’s better with<br>a little <em>company.</em></h2>
                        <p>A familiar face. A fresh start.<br>A place that feels a little more like you.</p>
                    </div>
                    <div class="story-bottom"><span class="mini-line"></span> A SMALL HELLO. A WHOLE LOT OF POSSIBILITY.</div>
                </section>
                <section class="form-panel" aria-label="<?= escape($title) ?>">
    <?php
}

function page_end(): void
{
    ?>
                </section>
            </main>
            <footer class="site-footer">
                <span>A happy little corner of the internet.</span>
                <span>Made for good company. <?= icon('heart') ?></span>
            </footer>
        </div>
    </body>
    </html>
    <?php
}

function field(string $name, string $label, string $type, string $placeholder, string $autocomplete, array $errors, array $values, string $fieldIcon): void
{
    $hasError = isset($errors[$name]);
    $password = $type === 'password';
    $description = $name . '-error' . ($name === 'password' && $autocomplete === 'new-password' ? ' password-guidance' : '');
    ?>
    <div class="field-group <?= $hasError ? 'has-error' : '' ?>">
        <label for="<?= escape($name) ?>"><?= escape($label) ?></label>
        <div class="input-wrap">
            <?= icon($fieldIcon, 'input-icon') ?>
            <input id="<?= escape($name) ?>" name="<?= escape($name) ?>" type="<?= escape($type) ?>" placeholder="<?= escape($placeholder) ?>" autocomplete="<?= escape($autocomplete) ?>" value="<?= $password ? '' : escape($values[$name] ?? '') ?>" required maxlength="<?= $password ? '72' : ($name === 'name' ? '80' : '254') ?>" aria-describedby="<?= escape($description) ?>" <?= $hasError ? 'aria-invalid="true"' : '' ?> <?= $name === 'email' ? 'autocapitalize="none" spellcheck="false"' : '' ?>>
            <?php if ($password): ?>
                <button class="password-toggle" type="button" aria-label="Show <?= $name === 'confirm_password' ? 'confirmation password' : 'password' ?>" aria-controls="<?= escape($name) ?>" aria-pressed="false" data-toggle-password="<?= escape($name) ?>"><?= icon('eye') ?><?= icon('eye-off', 'hidden') ?></button>
            <?php endif; ?>
        </div>
        <span class="field-error" id="<?= escape($name) ?>-error" aria-live="polite"><?= escape($errors[$name] ?? '') ?></span>
    </div>
    <?php
}
