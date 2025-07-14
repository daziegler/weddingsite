<?php

declare(strict_types=1);

namespace WeddingSite\Controllers;

abstract readonly class AbstractController implements ControllerInterface
{
    abstract public function handle(): void;

    protected function sanitize(string $value): string
    {
        return trim(htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
    }
}