<?php

declare(strict_types=1);

namespace WeddingSite\Infrastructure\Gallery;

interface ImageDerivativeServiceInterface
{
    public function ensure(ImageDerivativeRequest $request): string;
}