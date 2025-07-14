<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use WeddingSite\Controllers\AbstractController;
use WeddingSite\Controllers\PasswordProtectedTrait;
use WeddingSite\Modules\Gallery;
use WeddingSite\Modules\GalleryItem;

final readonly class GetGalleryController extends AbstractController
{
    use PasswordProtectedTrait;

    private string $uploadDir;
    private string $passwordFile;

    public function __construct()
    {
        $projectRoot = dirname(__DIR__, 3);
        $this->uploadDir = sprintf('%s/uploads/', $projectRoot);
        $this->passwordFile = sprintf('%s/.secrets/gallery-password.txt', $projectRoot);
    }

    public function handle(): void
    {
        $this->authenticateUser();
        $this->renderGallery();
    }

    private function renderGallery(): void
    {
        $files = glob($this->uploadDir . '*.{jpg,jpeg,png,webp}', GLOB_BRACE);
        $fileUrls = array_map(fn($file) => basename($file), $files);

        $galleryItems = array_map(
            fn($url) => new GalleryItem($url),
            $fileUrls
        );
        echo (new Gallery($galleryItems))->render();
    }

    protected function authSessionKey(): string
    {
        return 'gallery_auth';
    }

    protected function passwordFile(): string
    {
        return $this->passwordFile;
    }

    protected function redirectAfterLogin(): string
    {
        return '/fotos';
    }
}
