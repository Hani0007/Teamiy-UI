<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $adminName;
    public $planName;
    public $cycle;
    public $subscriptionId;

    /**
     * Create a new message instance.
     */
    public function __construct($adminName, $planName, $cycle, $subscriptionId)
    {
        $this->adminName = $adminName;
        $this->planName = $planName;
        $this->cycle = $cycle;
        $this->subscriptionId = $subscriptionId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Confirmation Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-received',
            with: [
                'adminName' => $this->adminName,
                'planName' => $this->planName,
                'cycle' => $this->cycle,
                'subscriptionId' => $this->subscriptionId,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
