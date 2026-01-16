<?php

declare(strict_types=1);

namespace WeddingSite;

use WeddingSite\Infrastructure\ImageVariant;

final readonly class Config
{
    public function __construct(
        private string $rootDirectory,
    ) {
    }

    private function rootDirectory(): string
    {
        return rtrim($this->rootDirectory, '/');
    }

    public function uploadDirectory(?ImageVariant $variant = null): string
    {
        if ($variant === null) {
            return sprintf('%s/uploads/', $this->rootDirectory());
        }
        return sprintf('%s/uploads/%s/', $this->rootDirectory(), $variant->subDir());
    }

    public function secretsDirectory(): string
    {
        return sprintf('%s/.secrets/', $this->rootDirectory());
    }
}