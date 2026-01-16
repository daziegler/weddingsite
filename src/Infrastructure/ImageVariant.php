<?php

declare(strict_types=1);

namespace WeddingSite\Infrastructure;

use WeddingSite\Infrastructure\Gallery\ImageException;

enum ImageVariant: string
{
    case ORIGINAL = 'original';
    case THUMBNAIL = 'thumbnail';


    public function maxWidth(): int
    {
        return match ($this) {
            self::THUMBNAIL => 480,
            self::ORIGINAL => throw ImageException::unsupportedVariant($this),
        };
    }

    public function quality(): int
    {
        return match ($this) {
            self::THUMBNAIL => 80,
            self::ORIGINAL => throw ImageException::unsupportedVariant($this),
        };
    }

    public function subDir(): string
    {
        return match ($this) {
            self::THUMBNAIL => 'thumb',
            default => $this->value,
        };
    }

}