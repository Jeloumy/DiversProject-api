<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->from('contacttournois@gmail.com')
            ->subject('Sujet de l\'e-mail')
            ->view('emails.mon_mail'); // Vue Blade pour le contenu de l'e-mail
    }
}
