<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class MusicRequestListItem extends AbstractModule
{
    public function __construct(private string $songTitle, private int $order) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceSongTitle($html);
        $html = $this->replaceOrder($html);

        return $html;
    }

    private function replaceSongTitle(string $html): string
    {
        return str_replace('[[MUSIC_REQUEST_LIST_ITEM]]', $this->songTitle, $html);
    }

    private function replaceOrder(string $html): string
    {
        return str_replace('[[MUSIC_REQUEST_LIST_ITEM_ORDER]]', (string) $this->order, $html);
    }
}
