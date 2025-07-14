<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class ContactItem extends AbstractModule
{
    public function __construct(
        private string $role,
        private string $name,
        private string $phone,
        private string $email
    ) {}

    public function render(): string
    {
        $html = $this->getTemplate();

        $html = $this->replaceRole($html);
        $html = $this->replaceName($html);
        $html = $this->replacePhone($html);
        $html = $this->replaceEmail($html);

        return $html;
    }

    private function replaceRole(string $html): string
    {
        return str_replace('[[CONTACT_ROLE]]', $this->role, $html);
    }

    private function replaceName(string $html): string
    {
        return str_replace('[[CONTACT_NAME]]', $this->name, $html);
    }

    private function replacePhone(string $html): string
    {
        $phone = $this->phone;
        $html = str_replace('[[CONTACT_PHONE]]', $phone, $html);
        $html = str_replace('[[CONTACT_PHONE_LINK]]', $this->formatPhoneLink($phone), $html);

        return $html;
    }

    private function formatPhoneLink(string $phone): string
    {
        $clean = preg_replace('/[^\d+]/', '', $phone);

        return preg_replace('/(?!^)\+/', '', $clean); // keep only the first "+"
    }

    private function replaceEmail(string $html): string
    {
        return str_replace('[[CONTACT_EMAIL]]', $this->email, $html);
    }
}