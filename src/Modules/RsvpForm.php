<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class RsvpForm extends AbstractModule
{
    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceFormAction($html);

        return $html;
    }

    private function replaceFormAction(string $html): string
    {
        $url = '/api/rsvp';

        return str_replace('[[RSVP_FORM_HANDLER_URL]]', $url, $html);
    }
}