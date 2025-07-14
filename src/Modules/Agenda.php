<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class Agenda extends AbstractModule
{
    /**
     * @param AgendaItem[] $items
     */
    public function __construct(private string $intro, private array $items) {}

    public function render(): string
    {
        $html = $this->getTemplate();

        $html = $this->replaceIntro($html);
        $html = $this->replaceItems($html);

        return $html;
    }

    private function replaceIntro(string $html): string
    {
        return str_replace('[[AGENDA_INTRO]]', nl2br($this->intro), $html);
    }

    private function replaceItems(string $html): string
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->render();
        }

        $eventsHtml = implode('', $items);

        return str_replace('[[AGENDA_ITEMS]]', $eventsHtml, $html);
    }
}