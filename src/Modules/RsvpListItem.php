<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class RsvpListItem extends AbstractModule
{
    public function __construct(private string $name, private string $status, private ?string $allergies = null) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceName($html);

        return $html;
    }

    private function replaceName(string $html): string
    {
        $name = $this->name;
        if ($this->status === 'yes' && $this->allergies !== null) {
            $name .= sprintf(' (Allergien: %s)', $this->allergies);
        }

        return str_replace('[[RSVP_LIST_ITEM]]', $name, $html);
    }
}