<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApiDocumentationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $section,
        public string $secret,
        public string $baseUrl,
        public array $types = [],
    ) {}

    public function envelope(): Envelope
    {
        $titles = [
            'deeplink' => 'Documentation API Deeplink',
            'register' => 'Documentation API Inscription',
            'webhook' => 'Documentation API Webhook sortant',
            'default' => 'Documentation API Abonnement par defaut',
            'subscriptions' => 'Documentation API Abonnements publiques',
        ];

        return new Envelope(
            subject: ($titles[$this->section] ?? 'Documentation API') . ' — bat-id Subscribers',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.api-documentation',
            with: [
                'section' => $this->section,
                'secret' => $this->secret,
                'baseUrl' => $this->baseUrl,
                'types' => $this->types,
            ],
        );
    }
}
