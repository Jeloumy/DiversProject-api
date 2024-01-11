<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonMail;
class EmailController extends Controller
{
    public function envoyerEmail()
    {
        $destinataire = 'jeremy60.duflot60@gmail.com';

        Mail::to($destinataire)->send(new MonMail());

        return 'E-mail envoyé avec succès !';
    }
}
