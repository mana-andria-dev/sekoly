<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;
use App\Models\User;

class SchoolAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $school; // 🔹 changement ici
    public User $user;
    public string $password;

    public function __construct(Tenant $school, User $user, string $password)
    {
        $this->school   = $school;
        $this->user     = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject("Vos accès EduSaaS")
                    ->view('emails.school-access'); // ton template email
    }
}
