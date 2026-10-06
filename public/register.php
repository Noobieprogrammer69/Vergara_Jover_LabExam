<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/app/bootstrap.php';
require_once dirname(__DIR__) . '/app/page.php';

if (!empty($_SESSION['user_id'])) {
    redirect('welcome.php');
}

$errors = [];
$values = ['name' => '', 'email' => ''];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name'] = trim(post_value('name'));
    $values['email'] = strtolower(trim(post_value('email')));
    $password = post_value('password');
    $confirmation = post_value('confirm_password');

    if (!valid_csrf()) {
        $errors['form'] = 'This form has expired. Please try again.';
    } else {
        if ($values['name'] === '') {
            $errors['name'] = 'Please enter your full name.';
        } elseif (!preg_match('/\A.{2,80}\z/us', $values['name']) || preg_match('/[\x00-\x1F\x7F]/', $values['name'])) {
            $errors['name'] = 'Use a name between 2 and 80 characters long.';
        }
        if ($error = validate_email($values['email'])) {
            $errors['email'] = $error;
        }
        if ($error = validate_password($password)) {
            $errors['password'] = $error;
        }
        if ($confirmation === '') {
            $errors['confirm_password'] = 'Please confirm your password.';
        } elseif ($password !== $confirmation) {
            $errors['confirm_password'] = 'Your passwords don’t match yet.';
        }

        if (!$errors) {
            try {
                $statement = database()->prepare('INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)');
                $statement->execute([
                    'name' => $values['name'],
                    'email' => $values['email'],
                    'password_hash' => password_hash($password, PASSWORD_BCRYPT),
                ]);
                $_SESSION['success'] = 'You’re part of the pack! Your account is ready. Log in to say hello.';
                $_SESSION['registered_email'] = $values['email'];
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                redirect('login.php');
            } catch (PDOException $exception) {
                if ((int) ($exception->errorInfo[1] ?? 0) === 19) {
                    $errors['email'] = 'An account with this email already exists. Try logging in.';
                } else {
                    error_log($exception->getMessage());
                    $errors['form'] = 'We couldn’t create your account right now. Please try again shortly.';
                }
            }
        }
    }
}

page_start('register');
require dirname(__DIR__) . '/app/auth-form.php';
page_end();

