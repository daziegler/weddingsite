<?php

declare(strict_types=1);

namespace WeddingSite;

use InvalidArgumentException;
use Throwable;
use WeddingSite\Controllers\API\AddMusicRequestEntryController;
use WeddingSite\Controllers\API\AddRsvpEntryController;
use WeddingSite\Controllers\API\GalleryDownloadController;
use WeddingSite\Controllers\API\UploadPhotoController;
use WeddingSite\Controllers\View\GetGalleryController;
use WeddingSite\Controllers\View\GetGalleryImageController;
use WeddingSite\Controllers\View\GetImageController;
use WeddingSite\Controllers\View\GetMusicRequestListController;
use WeddingSite\Controllers\View\GetRsvpListController;
use WeddingSite\Controllers\View\HomeController;
use WeddingSite\Controllers\View\ThankYouController;
use WeddingSite\Infrastructure\Gallery\ImageDerivativeService;
use WeddingSite\Infrastructure\HttpException;
use WeddingSite\Infrastructure\ImageVariant;

final class Router
{
    public function __construct(private readonly Config $config)
    {
    }

    public function route(string $uri): void
    {
        try {
            switch ($uri) {
                case '/rsvp':
                    $controller = new GetRsvpListController();
                    $controller->handle();
                    break;

                case '/api/rsvp':
                    $controller = new AddRsvpEntryController();
                    $controller->handle();
                    break;

                case '/musik':
                    $controller = new GetMusicRequestListController();
                    $controller->handle();
                    break;

                case '/api/music':
                    $controller = new AddMusicRequestEntryController();
                    $controller->handle();
                    break;

                case '/fotos':
                    $controller = new GetGalleryController(
                        $this->config->uploadDirectory(ImageVariant::ORIGINAL),
                        $this->config->secretsDirectory()
                    );
                    $controller->handle();
                    break;

                case '/foto':
                    $imageDerivativeService = new ImageDerivativeService($this->config->uploadDirectory());
                    $controller = new GetGalleryImageController($imageDerivativeService);
                    $controller->handle();
                    break;

                case '/i':
                    $controller = new GetImageController();
                    $controller->handle();
                    break;

                case '/api/upload-photo':
                    $controller = new UploadPhotoController();
                    $controller->handle();
                    break;

                case '/api/gallery-download':
                    $controller = new GalleryDownloadController(
                        $this->config->uploadDirectory(ImageVariant::ORIGINAL),
                    );
                    $controller->handle();
                    break;

                case '/danke':
                    $controller = new ThankYouController();
                    $controller->handle();
                    break;

                case '/':
                    $controller = new HomeController();
                    $controller->handle();
                    break;

                default:
                    throw HttpException::notFound('404 Not Found');
            }
        } catch (HttpException $e) {
            http_response_code($e->statusCode());
            echo $e->getMessage();
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo $e->getMessage();
        } catch (Throwable) {
            http_response_code(500);
            echo 'Internal Server Error';
        }
    }
}