<?php

declare(strict_types=1);

namespace WeddingSite\Controllers;

interface ControllerInterface
{
    public function handle(): void;
}