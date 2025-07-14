<?php

declare(strict_types=1);

namespace WeddingSite\Controllers;

use WeddingSite\Modules\LoginForm;

trait PasswordProtectedTrait
{
    private function authenticateUser(): void
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
            if ($_POST['password'] === $this->getPassword()) {
                $_SESSION[$this->authSessionKey()] = true;
                header('Location: ' . $this->redirectAfterLogin());
                exit;
            } else {
                $this->renderLoginForm('Falsches Passwort.');
                exit;
            }
        }

        if (($_SESSION[$this->authSessionKey()] ?? false) !== true) {
            $this->renderLoginForm();
            exit;
        }
    }

    private function getPassword(): string
    {
        $file = $this->passwordFile();
        if (!is_readable($file)) {
            http_response_code(500);
            exit('Server error.');
        }

        $password = file_get_contents($file);
        if ($password === false) {
            http_response_code(500);
            exit('Server error.');
        }

        return trim($password);
    }

    abstract protected function passwordFile(): string;

    /**
     * Override these in the controller
     */
    abstract protected function authSessionKey(): string;

    abstract protected function redirectAfterLogin(): string;

    private function renderLoginForm(string $error = ''): void
    {
        echo (new LoginForm($error))->render();
    }
}