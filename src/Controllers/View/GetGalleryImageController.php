<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use WeddingSite\Controllers\AbstractController;
use WeddingSite\Infrastructure\Gallery\ImageDerivativeRequest;
use WeddingSite\Infrastructure\Gallery\ImageDerivativeServiceInterface;
use WeddingSite\Infrastructure\Gallery\ImageException;
use WeddingSite\Infrastructure\HttpException;
use WeddingSite\Infrastructure\ImageVariant;

final readonly class GetGalleryImageController extends AbstractController
{
    private const array ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function __construct(private ImageDerivativeServiceInterface $imageDerivativeService)
    {
    }

    public function handle(): void
    {
        $this->assertAuthenticated();
        $fileParam = rawurldecode($_GET['file'] ?? ''); // prevent directory traversal

        $variant = ImageVariant::tryFrom($_GET['variant'] ?? '') ?? ImageVariant::ORIGINAL;
        try {
            $request = new ImageDerivativeRequest($fileParam, $variant);
            $filepath = $this->imageDerivativeService->ensure($request);
        } catch (ImageException $e) {
            $this->handleImageException($e);
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

    private function handleImageException(ImageException $e): never
    {
        throw match ($e->getCode()) {
            ImageException::ORIGINAL_NOT_FOUND => HttpException::notFound('Image not found'),
            ImageException::INVALID_FILENAME => HttpException::badRequest('Invalid filename'),
            ImageException::UNSUPPORTED_VARIANT => HttpException::badRequest('Bad Image Variant'),
            ImageException::DERIVATIVE_FAILED => HttpException::internal('Could not create derivative'),
            default => HttpException::internal('Unknown image error'),
        };
    }

    private function assertAuthenticated(): void
    {
        session_start();
        if (($_SESSION['gallery_auth'] ?? false) === true) {
            return;
        }

        throw HttpException::unauthorized();
    }
}
