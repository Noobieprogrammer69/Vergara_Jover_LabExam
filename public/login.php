<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
require_once dirname(__DIR__) . '/app/page.php';

if (!empty($_SESSION['user_id'])) {
    redirect('welcome.php');
}

$errors = [];
$values = ['email' => $_SESSION['registered_email'] ?? ''];
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success'], $_SESSION['registered_email']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['email'] = strtolower(trim(post_value('email')));
    $password = post_value('password');

    if (!valid_csrf()) {
        $errors['form'] = 'This form has expired. Please try again.';
    } else {
        if ($error = validate_email($values['email'])) {
            $errors['email'] = $error;
        }
        if ($password === '') {
            $errors['password'] = 'Please enter your password.';
        } elseif (strlen($password) > 72 || str_contains($password, "\0")) {
            $errors['password'] = 'Please check your password and try again.';
        }

        $attempts = array_filter($_SESSION['login_attempts'] ?? [], static fn (int $time): bool => $time > time() - 60);
        if (count($attempts) >= 8) {
            $errors['form'] = 'Too many sign-in attempts. Please try again in a minute.';
        }

        if (!$errors) {
            try {
                $statement = database()->prepare('SELECT id, password_hash FROM users WHERE email = :email');
                $statement->execute(['email' => $values['email']]);
                $user = $statement->fetch();
                // Check a dummy hash too, so unknown accounts follow the same slow path.
                $hash = $user['password_hash'] ?? '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi';
                $verified = password_verify($password, $hash);
                if ($user && $verified) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = (int) $user['id'];
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    unset($_SESSION['login_attempts']);
                    redirect('welcome.php');
                }
                $attempts[] = time();
                $_SESSION['login_attempts'] = $attempts;
                $errors['form'] = 'That email and password don’t match. Please try again.';
            } catch (PDOException $exception) {
                error_log($exception->getMessage());
                $errors['form'] = 'We couldn’t sign you in right now. Please try again shortly.';
            }
        }
    }
}

page_start('login');
require dirname(__DIR__) . '/app/auth-form.php';
page_end();

