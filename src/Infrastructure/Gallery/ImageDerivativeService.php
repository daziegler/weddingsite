<?php

declare(strict_types=1);

namespace WeddingSite\Infrastructure\Gallery;

use Imagick;
use ImagickException;
use Throwable;
use WeddingSite\Infrastructure\ImageVariant;

final class ImageDerivativeService implements ImageDerivativeServiceInterface
{
    private const string FILE_EXTENSION = 'webp';
    private const string IMAGE_FORMAT = 'webp';

    public function __construct(
        private readonly string $baseUploadDir,
    ) {
    }

    /**
     * @throws ImageException
     */
    public function ensure(ImageDerivativeRequest $request): string
    {
        $originalPath = $this->getOriginalPath($request->originalFileName());
        if (is_file($originalPath) === false) {

            echo('<pre>');
            var_dump($originalPath);
            die;

            throw ImageException::originalNotFound($request->originalFileName());
        }

        if ($request->variant() === ImageVariant::ORIGINAL) {
            return $originalPath;
        }

        $targetPath = $this->getTargetPath($request);
        if (is_file($targetPath) === false) {
            $this->generateDerivative($request->variant(), $originalPath, $targetPath);
        }

        return $targetPath;
    }

    private function getOriginalPath(string $filename): string
    {
        return sprintf('%s/%s/%s', $this->baseUploadDir, ImageVariant::ORIGINAL->value, $filename);
    }

    private function getTargetPath(ImageDerivativeRequest $request): string
    {
        $baseName = pathinfo($request->originalFileName(), PATHINFO_FILENAME);
        $variant = $request->variant();

        return sprintf('%s/%s/%s.%s', $this->baseUploadDir, $variant->subDir(), $baseName, self::FILE_EXTENSION);
    }

    private function generateDerivative(ImageVariant $variant, string $sourcePath, string $destPath): void
    {
        [$maxWidth, $quality] = [$variant->maxWidth(), $variant->quality()];

        try {
            $this->generateDerivativeFile($sourcePath, $destPath, $maxWidth, $quality);
        } catch (Throwable $e) {
            throw ImageException::derivativeGenerationFailed($variant, $sourcePath, $e);
        }
    }

    /**
     * @throws ImagickException
     */
    private function generateDerivativeFile(string $sourcePath, string $destPath, int $maxWidth, int $quality): void
    {
        $image = new Imagick($sourcePath);

        // Fix EXIF orientation if present
        $image->autoOrient();

        $width = $image->getImageWidth();
        $height = $image->getImageHeight();

        if ($width > $maxWidth) {
            $newHeight = (int)round($height * $maxWidth / $width);
            $image->resizeImage(
                $maxWidth,
                $newHeight,
                Imagick::FILTER_LANCZOS,
                1.0
            );
        }

        $image->setImageFormat(self::IMAGE_FORMAT);
        $image->setImageCompressionQuality($quality);

        $dir = dirname($destPath);
        if (is_dir($dir) === false) {
            mkdir($dir, 0775, true);
        }

        $image->writeImage($destPath);
        $image->clear();
    }
}