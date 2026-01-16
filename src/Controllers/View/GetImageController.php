<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use WeddingSite\Controllers\AbstractController;
use WeddingSite\Infrastructure\HttpException;

final readonly class GetImageController extends AbstractController
{
    private const array ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];
    private string $uploadDir;

    public function __construct()
    {
        $this->uploadDir = sprintf('%s/.images/', dirname(__DIR__, 3));
    }

    public function handle(): void
    {
        if (isset($_GET['file']) === false) {
            throw HttpException::badRequest('Missing file parameter');
        }

        $filename = basename(rawurldecode($_GET['file'])); // prevent directory traversal
        $filepath = $this->uploadDir . $filename;

        if (file_exists($filepath) === false) {
            throw HttpException::notFound('Image not found');
        }

        $mime = mime_content_type($filepath);
        if (in_array($mime, self::ALLOWED_MIME_TYPES, true) === false) {
            throw HttpException::forbidden();
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: private, max-age=3600');
        header('Content-Disposition: inline');

        readfile($filepath);
    }
}
