<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class Directions extends AbstractModule
{
    public function __construct(private string $intro, private string $googleMapsEmbedUrl) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceIntro($html);
        $html = $this->replaceGoogleMapsEmbedUrl($html);

        return $html;
    }

    private function replaceIntro(string $html): string
    {
        return str_replace('[[DIRECTIONS_INTRO]]', nl2br($this->intro), $html);
    }

    private function replaceGoogleMapsEmbedUrl(string $html): string
    {
        return str_replace('[[MAP_EMBED]]', $this->googleMapsEmbedUrl, $html);
    }
}