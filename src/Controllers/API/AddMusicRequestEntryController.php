<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\API;

use WeddingSite\Controllers\AbstractController;

final readonly class AddMusicRequestEntryController extends AbstractController
{
    public function handle(): void
    {
        $file = sprintf('%s/files/music_requests.json', dirname(__DIR__, 3));

        // Ensure data is coming via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method not allowed';
            exit;
        }

        $songs = [];
        if (isset($_POST['songs']) && is_array($_POST['songs'])) {
            foreach ($_POST['songs'] as $song) {
                $song = $this->sanitize($song);
                if ($song === '') {
                    continue;
                }
                $songs[] = [
                    'timestamp' => time(),
                    'song' => $song,
                ];
            }
        }

        if ($songs === []) {
            http_response_code(400);
            echo 'No valid songs submitted';
            exit;
        }

        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($data)) {
            $data = [];
        }

        foreach ($songs as $songEntry) {
            $data[] = $songEntry;
        }

        usort($data, fn($a, $b) => $a['timestamp'] <=> $b['timestamp']);

        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        header('Location: /danke');
        exit;
    }
}
