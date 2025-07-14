<?php

declare(strict_types=1);

namespace WeddingSite;

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

final class Router
{
    public function route(string $uri): void
    {
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
                $controller = new GetGalleryController();
                $controller->handle();
                break;

            case '/foto':
                $controller = new GetGalleryImageController();
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
                $controller = new GalleryDownloadController();
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
                http_response_code(404);
                echo "404 Not Found";
        }
    }
}