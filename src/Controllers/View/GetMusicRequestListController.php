<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use WeddingSite\Controllers\AbstractController;
use WeddingSite\Modules\MusicRequestList;
use WeddingSite\Modules\MusicRequestListItem;

final readonly class GetMusicRequestListController extends AbstractController
{
    private string $storageFile;

    public function __construct()
    {
        $storageFile = sprintf('%s/files/music_requests.json', dirname(__DIR__, 3));

        if (!file_exists($storageFile)) {
            file_put_contents($storageFile, json_encode([]));
        }

        $this->storageFile = $storageFile;
    }

    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo 'Method not allowed';
            exit;
        }

        $listItems = $this->extractSortedSongs();

        echo (new MusicRequestList($listItems))->render();
    }

    /**
     * @return MusicRequestListItem[]
     */
    private function extractSortedSongs(): array
    {
        $file = $this->storageFile;
        $data = json_decode(file_get_contents($file), true);

        if (!is_array($data)) {
            return [];
        }

        usort($data, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

        $items = [];
        $order = 1;
        foreach ($data as $entry) {
            if (isset($entry['song']) === false) {
                continue;
            }
            $items[] = new MusicRequestListItem($entry['song'], $order);
            $order++;
        }

        return $items;
    }
}