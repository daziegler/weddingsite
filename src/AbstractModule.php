<?php

declare(strict_types=1);

namespace WeddingSite;

use ReflectionClass;

abstract readonly class AbstractModule implements Module
{
    public function render(): string
    {
        $html = $this->getTemplate();

        return $html;
    }

    protected function getTemplate(): string
    {
        $moduleName = $this->name();
        $templatePath = sprintf('%s/templates/%s/view.html', dirname(__DIR__), $moduleName);

        if (!file_exists($templatePath)) {
            return sprintf('<p>Error: %s template not found.</p>', $moduleName);
        }

        return file_get_contents($templatePath);
    }

    public function name(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}