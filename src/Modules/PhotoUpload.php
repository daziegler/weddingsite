<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class PhotoUpload extends AbstractModule
{
    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceFormAction($html);

        return $html;
    }

    private function replaceFormAction(string $html): string
    {
        $url = '/api/upload-photo';

        return str_replace('[[PHOTO_UPLOAD_URL]]', $url, $html);
    }
}