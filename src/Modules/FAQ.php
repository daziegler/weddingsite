<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class FAQ extends AbstractModule
{
    /**
     * @param FaqItem[] $faqItems
     */
    public function __construct(private array $faqItems) {}

    public function render(): string
    {
        $html = $this->getTemplate();
        $html = $this->replaceFaqItems($html);

        return $html;
    }

    private function replaceFaqItems(string $html): string
    {
        $renderedItems = '';
        foreach ($this->faqItems as $faqItem) {
            $renderedItems .= $faqItem->render();
        }

        return str_replace('[[FAQ_ITEMS]]', $renderedItems, $html);
    }
}