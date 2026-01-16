<?php

declare(strict_types=1);

namespace WeddingSite\Infrastructure\Gallery;

use InvalidArgumentException;
use WeddingSite\Infrastructure\ImageVariant;

final readonly class ImageDerivativeRequest
{
    private string $originalFileName;

    public function __construct(string $originalFileName, private ImageVariant $variant)
    {
        // Prevent directory traversal
        $cleanName = basename($originalFileName);
        if ($cleanName === '' || $cleanName !== $originalFileName) {
            throw new InvalidArgumentException('Invalid filename supplied.');
        }

        $this->originalFileName = $cleanName;
    }

    public function originalFileName(): string
    {
        return $this->originalFileName;
    }

    public function variant(): ImageVariant
    {
        return $this->variant;
    }
}