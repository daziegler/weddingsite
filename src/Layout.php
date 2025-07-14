<?php

declare(strict_types=1);

namespace WeddingSite;

final readonly class Layout
{
    /**
     * @param array{
     *     wedding_date: string,
     *     wedding_timezone: string,
     *     title: string,
     *     headline: string,
     * } $settings
     * @param Module[] $modules
     */
    public function __construct(private array $settings, private array $modules) {}

    public function render(): string
    {
        $html = $this->getBaseTemplate();

        $html = $this->renderTitle($html);
        $html = $this->renderHeadline($html);
        $html = $this->renderImages($html);
        $html = $this->renderModules($html);
        $html = $this->replaceAllMissingPlaceholders($html);

        return $html;
    }

    private function getBaseTemplate(): string
    {
        $baseTemplatePath = sprintf('%s/templates/base.html', dirname(__DIR__));
        if (!file_exists($baseTemplatePath)) {
            return '<p>Error: Base template not found.</p>';
        }

        return file_get_contents($baseTemplatePath);
    }

    private function renderTitle(string $html): string
    {
        $title = $this->settings['title'];
        $placeholder = '[[TITLE]]';

        return str_replace($placeholder, $title, $html);
    }

    private function renderHeadline(string $html): string
    {
        $headline = $this->settings['headline'];
        $placeholder = '[[HEADLINE]]';

        return str_replace($placeholder, $headline, $html);
    }

    private function renderImages(string $html): string
    {
        $images = [
            '[[HEADERIMAGE]]' => '/i?file=header.webp',
        ];

        foreach ($images as $placeholder => $imageUrl) {
            $html = str_replace($placeholder, $imageUrl, $html);
        }

        return $html;
    }

    private function renderModules(string $html): string
    {
        foreach ($this->modules as $module) {
            $html = $this->renderModule($html, $module);
        }

        return $html;
    }

    private function renderModule(string $template, Module $module): string
    {
        $placeholder = sprintf('[[%s]]', strtoupper($module->name()));

        return str_replace($placeholder, $module->render(), $template);
    }

    private function replaceAllMissingPlaceholders(string $html): string
    {
        return preg_replace('/\[\[.*?\]\]/', '', $html) ?: $html;
    }
}