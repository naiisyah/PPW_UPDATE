<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    // digunakan untuk menginisialisais objek yang digunakan pada template email
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    // digunakan untuk mengatur struktur email yang lebih spesifik(melakukan konfigurasi email, menampilkan template email, menambahkan attachment)
    public function build()
    {
        return $this->subject($this->data['subject'])
 ->view('emails.sendemail');
    }

    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Send Email',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
