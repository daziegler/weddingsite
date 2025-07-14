<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class IntroText extends AbstractModule
{
    public function __construct(private string $introHeadline, private string $introText) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceHeadline($html);
        $html = $this->replaceText($html);

        return $html;
    }

    private function replaceHeadline(string $html): string
    {
        return str_replace('[[INTRO_HEADLINE]]', nl2br($this->introHeadline), $html);
    }

    private function replaceText(string $html): string
    {
        return str_replace('[[INTRO_TEXT]]', nl2br($this->introText), $html);
    }
}