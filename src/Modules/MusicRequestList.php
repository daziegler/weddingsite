<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class MusicRequestList extends AbstractModule
{
    /** @var MusicRequestListItem[] */
    private array $entries;

    /**
     * @param MusicRequestListItem[] $entries
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }

    public function render(): string
    {
        $html = $this->getTemplate();

        $listHtml = '';
        foreach ($this->entries as $entry) {
            $listHtml .= $entry->render();
        }

        return str_replace('[[MUSIC_REQUEST_LIST]]', $listHtml, $html);
    }
}