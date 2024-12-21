<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registrasiemail extends Mailable

{
    use Queueable, SerializesModels;
    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function build()
    {
        return $this->view('emails.registrasi_success')
                    ->with(['user', $this->user]);
    }
}