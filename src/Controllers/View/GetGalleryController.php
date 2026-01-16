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

    private string $passwordFile;

    public function __construct(private string $originalUploadDirectory, string $secretsDirectory)
    {
        $this->passwordFile = sprintf('%s/gallery-password.txt', $secretsDirectory);
    }

    public function handle(): void
    {
        $this->authenticateUser();
        $this->renderGallery();
    }

    private function renderGallery(): void
    {
        $files = glob(sprintf('%s*.{jpg,JPG,jpeg,JPEG,png,PNG,webp,WEBP}', $this->originalUploadDirectory), GLOB_BRACE);
        $fileNames = array_map(fn($file) => basename($file), $files);

        $galleryItems = array_map(
            fn($fileName) => new GalleryItem($fileName),
            $fileNames
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
