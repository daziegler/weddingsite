<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class FaqIntro extends AbstractModule
{
    public function render(): string
    {
        $html = $this->getTemplate();

        return $html;
    }

    protected function getTemplate(): string
    {
        $moduleName = $this->name();
        $templatePath = sprintf('%s/.optional-templates/%s/view.html', dirname(__DIR__, 2), $moduleName);
        if (!file_exists($templatePath)) {
            return sprintf('<p>Error: %s template not found.</p>', $moduleName);
        }

        return file_get_contents($templatePath);
    }
}