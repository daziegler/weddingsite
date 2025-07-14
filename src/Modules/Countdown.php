<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use DateTimeImmutable;
use WeddingSite\AbstractModule;

final readonly class Countdown extends AbstractModule
{
    public function __construct(private DateTimeImmutable $targetDate) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceWeddingDate($html);

        return $html;
    }

    private function replaceWeddingDate(string $html): string
    {
        $formattedDate = $this->targetDate->format('Y-m-d H:i:s');

        return str_replace('[[WEDDING_DATE]]', $formattedDate, $html);
    }
}
