<?php

namespace App\Mail;

use App\Models\OrderRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public OrderRequest $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'NightDriver: новая заявка — '.$this->order->phone,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.order-received',
            with: ['order' => $this->order],
        );
    }
}
