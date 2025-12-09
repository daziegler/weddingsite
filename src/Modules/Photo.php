<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class Photo extends AbstractModule
{
    public function __construct(private ?GalleryLink $galleryLink, private ?PhotoUpload $photoUpload) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceGalleryLink($html);
        $html = $this->replacePhotoUpload($html);

        return $html;
    }

    private function replaceGalleryLink(string $html): string
    {
        if ($this->galleryLink === null) {
            return str_replace('[[GALLERYLINK]]', '', $html);
        }

        return str_replace('[[GALLERYLINK]]', $this->galleryLink->render(), $html);
    }

    private function replacePhotoUpload(string $html): string
    {
        if ($this->photoUpload === null) {
            return str_replace('[[PHOTOUPLOAD]]', '', $html);
        }

        return str_replace('[[PHOTOUPLOAD]]', $this->photoUpload->render(), $html);
    }
}