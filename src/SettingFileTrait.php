<?php

declare(strict_types=1);

namespace WeddingSite;

use RuntimeException;
use UnexpectedValueException;

trait SettingFileTrait
{
    private function readSettingsFile(string $filename): array
    {
        $filePath = sprintf('%s/.settings/%s', dirname(__DIR__), $filename);
        if (file_exists($filePath) === false) {
            throw new RuntimeException(sprintf('Settings file "%s" not found.', $filePath));
        }

        $data = json_decode(file_get_contents($filePath), true);
        if (is_array($data) === false) {
            throw new UnexpectedValueException(sprintf('Invalid data in settings file "%s".', $filePath));
        }

        return $data;
    }
}