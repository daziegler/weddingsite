<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class LoginForm extends AbstractModule
{
    public function __construct(private string $errorMessage) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceErrorMessage($html);

        return $html;
    }

    private function replaceErrorMessage(string $html): string
    {
        return str_replace('[[LOGIN_ERROR]]', $this->errorMessage, $html);
    }
}