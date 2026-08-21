<?php

namespace App\Mail;

use App\Models\OwnerPenalty;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OwnerPenaltyMail extends Mailable
{
    use Queueable, SerializesModels;

    public OwnerPenalty $penalty;
    public Report $report;

    /**
     * Create a new message instance.
     */
    public function __construct(OwnerPenalty $penalty, Report $report)
    {
        $this->penalty = $penalty;
        $this->report = $report;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo xử lý vi phạm chủ xe - ' . $this->penalty->penalty_type?->getLabel(),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.owner_penalty',
            with: [
                'penalty' => $this->penalty,
                'report' => $this->report,
                'owner' => $this->penalty->user,
                'penaltyLabel' => $this->penalty->penalty_type?->getLabel(),
                'reason' => $this->penalty->reason,
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
