<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class MusicRequestForm extends AbstractModule
{
    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceFormAction($html);

        return $html;
    }

    private function replaceFormAction(string $html): string
    {
        $url = '/api/music';

        return str_replace('[[MUSIC_REQUEST_FORM_HANDLER_URL]]', $url, $html);
    }
}