<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class PhotoHighlight extends AbstractModule
{
    /**
     * @param PhotoHighlightItem[] $photoHighlightItems
     */
    public function __construct(private array $photoHighlightItems) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replacePhotoUrls($html);

        return $html;
    }

    private function replacePhotoUrls(string $html): string
    {
        $photoHtml = '';
        foreach ($this->photoHighlightItems as $item) {
            $photoHtml .= $item->render();
        }

        return str_replace('[[PHOTO_HIGHLIGHT_ITEMS]]', $photoHtml, $html);
    }
}