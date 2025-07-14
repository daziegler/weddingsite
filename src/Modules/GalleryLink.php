<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class GalleryLink extends AbstractModule
{
    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceGalleryLink($html);

        return $html;
    }

    private function replaceGalleryLink(string $html): string
    {
        return str_replace('[[GALLERY_LINK]]', '/fotos', $html);
    }
}