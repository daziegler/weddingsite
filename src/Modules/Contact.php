<?php

declare(strict_types=1);

namespace WeddingSite\Modules;

use WeddingSite\AbstractModule;

final readonly class Contact extends AbstractModule
{
    /**
     * @param ContactItem[] $contacts
     */
    public function __construct(private array $contacts) {}

    public function render(): string
    {
        $html = $this->getTemplate();

        $renderedContacts = '';
        foreach ($this->contacts as $contact) {
            $renderedContacts .= $contact->render();
        }

        return str_replace('[[CONTACT_LIST]]', $renderedContacts, $html);
    }
}