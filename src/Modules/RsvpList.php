<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class RsvpList extends AbstractModule
{
    /**
     * @param RsvpListItem[] $yesListItems
     * @param RsvpListItem[] $noListItems
     */
    public function __construct(private array $yesListItems, private array $noListItems) {}

    public function render(): string
    {
        $html = $this->getTemplate();

        $html = $this->replaceYesList($html);
        $html = $this->replaceNoList($html);

        return $html;
    }

    private function replaceYesList(string $html): string
    {
        $yesList = '';
        foreach ($this->yesListItems as $item) {
            $yesList .= $item->render();
        }

        return str_replace('[[RSVP_LIST_YES]]', $yesList, $html);
    }

    private function replaceNoList(string $html): string
    {
        $noList = '';
        foreach ($this->noListItems as $item) {
            $noList .= $item->render();
        }

        return str_replace('[[RSVP_LIST_NO]]', $noList, $html);
    }
}