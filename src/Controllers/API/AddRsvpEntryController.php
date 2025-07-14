<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\API;

use WeddingSite\Controllers\AbstractController;

final readonly class AddRsvpEntryController extends AbstractController
{
    public function handle(): void
    {
        $file = sprintf('%s/files/rsvps.json', dirname(__DIR__, 3));

        // Ensure data is coming via POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo 'Method not allowed';
            exit;
        }
        $guests = [];
        if (isset($_POST['guests']) && is_array($_POST['guests'])) {
            foreach ($_POST['guests'] as $guest) {
                $name = $this->sanitize($guest['name'] ?? '');
                $status = $this->sanitize($guest['status'] ?? '');
                if ($name === '' || $status === '') {
                    continue;
                }
                $allergies = $this->sanitize($guest['allergies'] ?? '');

                $guests[$status][] = [
                    'name' => $name,
                    'allergies' => $allergies,
                ];
            }
        }

        // Build the final entry
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'guests' => $guests,
        ];

        // Load and append to JSON
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($data)) {
            $data = [];
        }

        $data[] = $entry;

        // Save back
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        header('Location: /danke');
        exit;
    }
}