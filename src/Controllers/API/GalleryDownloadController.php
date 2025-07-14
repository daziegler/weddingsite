<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\API;

use WeddingSite\Controllers\AbstractController;
use ZipArchive;

final readonly class GalleryDownloadController extends AbstractController
{
    public function handle(): void
    {
        $this->assertAuthenticated();

        $uploadDir = sprintf('%s/uploads/', dirname(__DIR__, 3));
        $images = glob($uploadDir . '*.{jpg,jpeg,png,webp}', GLOB_BRACE);

        if ($images === false || $images === []) {
            http_response_code(404);
            echo 'No images found';
            exit;
        }

        $zipFile = tempnam(sys_get_temp_dir(), 'gallery_') . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipFile, ZipArchive::CREATE) !== true) {
            http_response_code(500);
            echo 'Could not create ZIP file';
            exit;
        }

        foreach ($images as $file) {
            $zip->addFile($file, basename($file));
        }

        $zip->close();

        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="HochzeitDS2025.zip"');
        header('Content-Length: ' . filesize($zipFile));

        readfile($zipFile);
        unlink($zipFile); // Clean up
        exit;
    }

    private function assertAuthenticated(): void
    {
        session_start();
        if (($_SESSION['gallery_auth'] ?? false) === true) {
            return;
        }

        http_response_code(401);
        echo 'Forbidden';
        exit;
    }
}
