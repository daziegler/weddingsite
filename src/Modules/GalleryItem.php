<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;
use WeddingSite\Infrastructure\ImageVariant;

final readonly class GalleryItem extends AbstractModule
{
    public function __construct(private string $fileName)
    {
    }

    public function render(): string
    {
        $html = $this->getTemplate();

        $html = $this->replaceOriginalFilename($html);
        $html = $this->replaceThumbnailFilename($html);

        return $html;
    }

    private function replaceOriginalFilename(string $html): string
    {
        return str_replace(
            '[[PHOTO_GALLERY_ORIGINAL_IMAGE_PATH]]',
            $this->buildImageUrl(ImageVariant::ORIGINAL),
            $html
        );
    }

    private function replaceThumbnailFilename(string $html): string
    {
        return str_replace(
            '[[PHOTO_GALLERY_THUMBNAIL_IMAGE_PATH]]',
            $this->buildImageUrl(ImageVariant::THUMBNAIL),
            $html
        );
    }

    private function buildImageUrl(ImageVariant $variant): string
    {
        return sprintf('/foto?file=%s&variant=%s', rawurlencode($this->fileName), $variant->value);
    }
}