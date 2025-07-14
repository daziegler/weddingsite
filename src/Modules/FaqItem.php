<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class FaqItem extends AbstractModule
{
    public function __construct(private string $question, private string $answer) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceQuestion($html);
        $html = $this->replaceAnswer($html);

        return $html;
    }

    private function replaceQuestion(string $html): string
    {
        return str_replace('[[FAQ_ITEM_QUESTION]]', $this->question, $html);
    }

    private function replaceAnswer(string $html): string
    {
        return str_replace('[[FAQ_ITEM_ANSWER]]', $this->answer, $html);
    }
}