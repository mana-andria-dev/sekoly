<?php
// app/Mail/SchoolRegistrationConfirmationMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;

class SchoolRegistrationConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $school;
    public string $domain;

    public function __construct(Tenant $school, string $domain)
    {
        $this->school = $school;
        $this->domain = $domain;
    }

    public function build()
    {
        return $this->subject("📋 Demande d'inscription reçue - " . $this->school->name)
                    ->view('emails.registration-confirmation');
    }
}