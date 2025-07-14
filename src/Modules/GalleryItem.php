<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class GalleryItem extends AbstractModule
{
    public function __construct(private string $imagePath) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $imageUrl = sprintf('/foto?file=%s', urlencode($this->imagePath));

        return str_replace('[[PHOTO_GALLERY_IMAGE_PATH]]', $imageUrl, $html);
    }
}