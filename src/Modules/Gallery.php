<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class Gallery extends AbstractModule
{
    /**
     * @param GalleryItem[] $galleryItems
     */
    public function __construct(private array $galleryItems) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceGalleryItems($html);
        $html = $this->replaceDownloadLink($html);

        return $html;
    }

    private function replaceGalleryItems(string $html): string
    {
        $galleryHtml = '';
        foreach ($this->galleryItems as $galleryItem) {
            $galleryHtml .= $galleryItem->render();
        }

        return str_replace('[[PHOTO_GALLERY_ITEMS]]', $galleryHtml, $html);
    }

    private function replaceDownloadLink(string $html): string
    {
        $downloadLink = '/api/gallery-download';

        return str_replace('[[GALLERY_DOWNLOAD_LINK]]', $downloadLink, $html);
    }
}