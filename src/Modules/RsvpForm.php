<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use DateTimeImmutable;
use WeddingSite\AbstractModule;

final readonly class RsvpForm extends AbstractModule
{
    public function __construct(private DateTimeImmutable $respondUntilDate) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceRespondUntilDate($html);
        $html = $this->replaceFormAction($html);

        return $html;
    }

    private function replaceRespondUntilDate(string $html): string
    {
        $respondUntilDate = $this->respondUntilDate->format('d.m.Y');

        return str_replace('[[RSVP_FORM_RESPOND_UNTIL]]', $respondUntilDate, $html);
    }

    private function replaceFormAction(string $html): string
    {
        $url = '/api/rsvp';

        return str_replace('[[RSVP_FORM_HANDLER_URL]]', $url, $html);
    }
}