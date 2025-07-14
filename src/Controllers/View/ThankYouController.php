<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use WeddingSite\Controllers\AbstractController;
use WeddingSite\Modules\ThankYou;

final readonly class ThankYouController extends AbstractController
{
    public function handle(): void
    {
        echo (new ThankYou())->render();
    }
}