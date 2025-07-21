<?php

declare(strict_types=1);

namespace WeddingSite\Controllers\View;

use DateTimeImmutable;
use DateTimeZone;
use WeddingSite\Controllers\AbstractController;
use WeddingSite\Layout;
use WeddingSite\Modules\Agenda;
use WeddingSite\Modules\AgendaItem;
use WeddingSite\Modules\Contact;
use WeddingSite\Modules\ContactItem;
use WeddingSite\Modules\Countdown;
use WeddingSite\Modules\Directions;
use WeddingSite\Modules\FAQ;
use WeddingSite\Modules\FaqIntro;
use WeddingSite\Modules\FaqItem;
use WeddingSite\Modules\GalleryLink;
use WeddingSite\Modules\IntroText;
use WeddingSite\Modules\MusicRequestForm;
use WeddingSite\Modules\PhotoHighlight;
use WeddingSite\Modules\PhotoHighlightItem;
use WeddingSite\Modules\PhotoUpload;
use WeddingSite\Modules\RsvpForm;
use WeddingSite\SettingFileTrait;

final readonly class HomeController extends AbstractController
{
    use SettingFileTrait;

    public function handle(): void
    {
        $modules = [];

        $weddingDateTime = $this->retrieveWeddingDateTime();
        $now = new DateTimeImmutable('now', $weddingDateTime->getTimezone());
        $isBeforeWedding = $now < $weddingDateTime;
        $isAfterWedding = $now >= $weddingDateTime->modify('+1 day');

        $settings = $this->readSettingsFile('general.json');

        $modules[] = new Countdown($weddingDateTime);
        $modules[] = new IntroText($settings['intro_headline'], $settings['intro_text']);

        $modules[] = $this->buildDirectionsModule();
        $modules[] = $this->buildAgendaModule();

        $modules[] = new FaqIntro();
        $modules[] = $this->buildFAQModule();

        $modules[] = $this->buildPhotoHighlightModule();

        // Modules that need to be disabled before the wedding starts
        if ($isBeforeWedding) {
            $modules[] = $this->buildContactModule();

            $respondUntilDate = $this->retrieveRespondUntilDate();
            $modules[] = new RsvpForm($respondUntilDate);
        }

        // Modules that need to be disabled after the wedding ends
        if ($isAfterWedding === false) {
            $modules[] = new MusicRequestForm();
        }

        if ($isAfterWedding === true) {
            $modules[] = new GalleryLink();
        }
        $modules[] = new PhotoUpload();

        $layout = new Layout($settings, $modules);
        echo $layout->render();
    }

    private function retrieveWeddingDateTime(): DateTimeImmutable
    {
        $data = $this->readSettingsFile('general.json');
        $weddingDate = $data['wedding_date'];
        $timezone = new DateTimeZone($data['wedding_timezone']);

        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $weddingDate, $timezone);
    }

    private function retrieveRespondUntilDate(): DateTimeImmutable
    {
        $data = $this->readSettingsFile('general.json');
        $weddingDate = $data['respond_until_date'];
        $timezone = new DateTimeZone($data['wedding_timezone']);

        return DateTimeImmutable::createFromFormat('Y-m-d', $weddingDate, $timezone);
    }

    private function buildDirectionsModule(): Directions
    {
        $data = $this->readSettingsFile('directions.json');
        $intro = $data['intro'];
        $embedLink = $data['link'];

        return new Directions($intro, $embedLink);
    }

    private function buildAgendaModule(): Agenda
    {
        $agendaSettings = $this->readSettingsFile('agenda.json');

        $agendaItems = [];
        foreach ($agendaSettings['entries'] as $entry) {
            $time = $entry['time'] ?? '';
            $title = $entry['title'] ?? '';
            if ($time === '' || $title === '') {
                continue;
            }
            $agendaItems[] = new AgendaItem($time, $title);
        }

        return new Agenda($agendaSettings['intro'], $agendaItems);
    }

    private function buildFAQModule(): FAQ
    {
        $faqItems = [];
        foreach ($this->readSettingsFile('faq.json') as $entry) {
            $question = $entry['question'] ?? '';
            $answer = $entry['answer'] ?? '';
            if ($question === '' || $answer === '') {
                continue;
            }
            $faqItems[] = new FaqItem($question, $answer);
        }

        return new FAQ($faqItems);
    }

    private function buildPhotoHighlightModule(): PhotoHighlight
    {
        return new PhotoHighlight(
            [
                new PhotoHighlightItem('highlight-1.webp'),
                new PhotoHighlightItem('highlight-2.webp'),
                new PhotoHighlightItem('highlight-3.webp'),
            ]
        );
    }

    private function buildContactModule(): Contact
    {
        $contacts = [];
        foreach ($this->readSettingsFile('contacts.json') as $entry) {
            $role = $entry['role'] ?? '';
            $name = $entry['name'] ?? '';
            $phone = $entry['phone'] ?? '';
            $email = $entry['email'] ?? '';
            if ($role === '' || $name === '' || $phone === '' || $email === '') {
                continue;
            }
            $contacts[] = new ContactItem($role, $name, $phone, $email);
        }

        return new Contact($contacts);
    }
}