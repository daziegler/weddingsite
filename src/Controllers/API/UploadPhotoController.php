<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\API;

use WeddingSite\Controllers\AbstractController;

final readonly class UploadPhotoController extends AbstractController
{
    private const array ALLOWED_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method Not Allowed';
            exit;
        }

        if (isset($_FILES['photo']) === false) {
            http_response_code(400);
            echo 'No file uploaded';
            exit;
        }

        $file = $_FILES['photo'];
        $this->assertFileIsValid($file);

        $uploadDir = $this->getUploadDir();
        $this->ensureDir($uploadDir);

        $saved = $this->saveUploadedFile($file, $uploadDir);
        if ($saved === false) {
            http_response_code(500);
            echo 'Upload failed';
            exit;
        }

        echo 'OK';
    }

    private function getUploadDir(): string
    {
        return sprintf('%s/uploads/original', dirname(__DIR__, 3));
    }

    private function ensureDir(string $dir): void
    {
        if (is_dir($dir)) {
            return;
        }
        mkdir($dir, 0777, true);
    }

    private function assertFileIsValid(array $file): void
    {
        if ($file['error'] === UPLOAD_ERR_OK && isset(self::ALLOWED_TYPES[$file['type']])) {
            return;
        }

        http_response_code(400);
        echo 'Invalid file';
        exit;
    }

    private function saveUploadedFile(array $file, string $uploadDir): bool
    {
        $fileExtension = self::ALLOWED_TYPES[$file['type']];
        $fileName = sprintf('%s.%s', uniqid('photo_', true), $fileExtension);
        $uploadFilePath = sprintf('%s%s', $uploadDir, $fileName);

        return move_uploaded_file($file['tmp_name'], $uploadFilePath);
    }
}