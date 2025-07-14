<?php

declare(strict_types=1);

namespace WeddingSite;

interface Module
{
    public function name(): string;

    public function render(): string;
}