<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class AgendaItem extends AbstractModule
{
    public function __construct(
        private string $time,
        private string $title,
    ) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->renderTime($html);
        $html = $this->renderTitle($html);

        return $html;
    }

    private function renderTime(string $html): string
    {
        return str_replace('[[AGENDA_ITEM_TIME]]', $this->time, $html);
    }

    private function renderTitle(string $html): string
    {
        return str_replace('[[AGENDA_ITEM_TITLE]]', $this->title, $html);
    }
}