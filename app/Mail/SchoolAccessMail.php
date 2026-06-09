<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Subscription;

class SchoolAccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public Tenant $school;
    public User $user;
    public string $password;
    public string $domain;
    public ?Subscription $subscription;

    public function __construct(Tenant $school, User $user, string $password, string $domain, ?Subscription $subscription = null)
    {
        $this->school = $school;
        $this->user = $user;
        $this->password = $password;
        $this->domain = $domain;
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->subject("🎉 Votre école est activée - " . $this->school->name)
                    ->view('emails.school-access');
    }
}