<?php

declare(strict_types=1);

namespace WeddingSite\Infrastructure\Gallery;

use RuntimeException;
use Throwable;
use WeddingSite\Infrastructure\ImageVariant;

final class ImageException extends RuntimeException
{
    public const int ORIGINAL_NOT_FOUND = 1001;
    public const int DERIVATIVE_FAILED = 1002;
    public const int UNSUPPORTED_VARIANT = 1003;
    public const int INVALID_FILENAME = 1004;

    private function __construct(string $message, int $code, ?Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);
    }

    public static function originalNotFound(string $filename): self
    {
        return new self(
            sprintf('Original image "%s" not found.', $filename),
            self::ORIGINAL_NOT_FOUND
        );
    }

    public static function derivativeGenerationFailed(
        ImageVariant $variant,
        string $filename,
        ?Throwable $prev = null
    ): self {
        return new self(
            sprintf('Failed to generate %s derivative for "%s".', $variant->value, $filename),
            self::DERIVATIVE_FAILED,
            $prev
        );
    }

    public static function unsupportedVariant(ImageVariant $variant): self
    {
        return new self(
            sprintf('Unsupported image variant "%s".', $variant->name),
            self::UNSUPPORTED_VARIANT
        );
    }

    public static function invalidFilename(string $name): self
    {
        return new self(
            sprintf('Invalid filename "%s".', $name),
            self::INVALID_FILENAME
        );
    }
}
