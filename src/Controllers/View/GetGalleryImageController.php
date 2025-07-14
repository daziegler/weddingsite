<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use WeddingSite\Controllers\AbstractController;

final readonly class GetGalleryImageController extends AbstractController
{
    private const array ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];
    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = sprintf('%s/uploads/', dirname(__DIR__, 3));
    }

    public function handle(): void
    {
        $this->assertAuthenticated();
        if (isset($_GET['file']) === false) {
            http_response_code(400);
            echo 'Missing file parameter';
            exit;
        }

        $filename = basename(urldecode($_GET['file'])); // prevent directory traversal
        $filepath = $this->uploadDir . $filename;

        if (file_exists($filepath) === false) {
            http_response_code(404);
            echo 'File not found';
            exit;
        }

        $mime = mime_content_type($filepath);
        if (in_array($mime, self::ALLOWED_MIME_TYPES, true) === false) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
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
