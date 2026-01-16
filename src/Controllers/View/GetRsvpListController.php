<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use WeddingSite\Controllers\AbstractController;
use WeddingSite\Controllers\PasswordProtectedTrait;
use WeddingSite\Infrastructure\HttpException;
use WeddingSite\Modules\RsvpList;
use WeddingSite\Modules\RsvpListItem;

final readonly class GetRsvpListController extends AbstractController
{
    use PasswordProtectedTrait;

    private string $storageFile;
    private string $passwordFile;

    public function __construct()
    {
        $projectRoot = dirname(__DIR__, 3);
        $this->storageFile = sprintf('%s/files/rsvps.json', $projectRoot);
        $this->passwordFile = sprintf('%s/.secrets/rsvp-password.txt', $projectRoot);
    }

    public function handle(): void
    {
        $this->authenticateUser();
        // Ensure data is coming via GET
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            throw HttpException::methodNotAllowed();
        }
        $listItems = $this->extractDataFromFile();

        echo (new RsvpList($listItems['yes'], $listItems['no']))->render();
    }

    /**
     * @return array{
     *    yes: RsvpListItem[],
     *    no: RsvpListItem[],
     * }
     */
    private function extractDataFromFile(): array
    {
        $file = $this->storageFile;
        $this->ensureFileExists($file);
        $data = json_decode(file_get_contents($file), true);

        $listItems = [
            'yes' => [],
            'no' => [],
        ];
        foreach ($data as $entry) {
            foreach ($entry['guests'] ?? [] as $status => $group) {
                foreach ($group as $guest) {
                    if (!isset($guest['name'])) {
                        continue;
                    }

                    $name = $guest['name'];
                    $allergies = null;
                    if ($status === 'yes' && $guest['allergies'] !== '') {
                        $allergies = $guest['allergies'];
                    }

                    $listItems[$status][] = new RsvpListItem(
                        $name,
                        $status,
                        $allergies
                    );
                }
            }
        }

        return $listItems;
    }

    private function ensureFileExists(string $file): void
    {
        if (file_exists($file)) {
            return;
        }
        file_put_contents($file, json_encode([]));
    }

    protected function authSessionKey(): string
    {
        return 'rsvp_auth';
    }

    protected function passwordFile(): string
    {
        return $this->passwordFile;
    }

    protected function redirectAfterLogin(): string
    {
        return '/rsvp';
    }
}