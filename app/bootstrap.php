<?php
declare(strict_types=1);

// Shared session, database, and form helpers. Private files live outside public/.
ini_set('session.use_strict_mode', '1');
session_name('pawspace_session');
session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
    'path' => '/',
]);
session_start();

header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self'; font-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'none'");

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function post_value(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? $value : '';
}

function redirect(string $location): never
{
    header('Location: ' . $location, true, 303);
    exit;
}

function csrf_token(): string
{
    return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
}

function valid_csrf(): bool
{
    return hash_equals(csrf_token(), post_value('csrf_token'));
}

function database(): PDO
{
    static $connection = null;
    if ($connection instanceof PDO) {
        return $connection;
    }

    $path = getenv('PAWSPACE_DB_PATH') ?: dirname(__DIR__) . '/storage/users.sqlite';
    $connection = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $connection->exec('PRAGMA busy_timeout = 5000');
    $connection->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL COLLATE NOCASE UNIQUE,
        password_hash TEXT NOT NULL,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    )');
    return $connection;
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    $statement = database()->prepare('SELECT id, name, email FROM users WHERE id = :id');
    $statement->execute(['id' => $_SESSION['user_id']]);
    return $statement->fetch() ?: null;
}

function validate_email(string $email): ?string
{
    if ($email === '') {
        return 'Please enter your email address.';
    }
    if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Enter a valid email address, like you@example.com.';
    }
    return null;
}

function validate_password(string $password): ?string
{
    if ($password === '') {
        return 'Please create a password.';
    }
    if (strlen($password) < 8 || strlen($password) > 72) {
        return 'Use a password between 8 and 72 bytes long.';
    }
    if (!preg_match('/[a-z]/', $password) || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        return 'Include an uppercase letter, a lowercase letter, and a number.';
    }
    if (str_contains($password, "\0")) {
        return 'Your password contains an unsupported character.';
    }
    return null;
}

