<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify', Carbon::now()->addMinutes(60), 
                ['id' => $this->user->getKey(), 'hash' => $this->user->email_verification_token]
            );
            $buttonUrl = URL::to('/verify-email');
            
            return (new MailMessage)
                ->subject('Verify Your Email Address')
                ->line('Please click the button below to verify your email address.')
                ->action('Verify Email Address', $verificationUrl)
                ->line('If you did not create an account, no further action is required.')
                ->action('Continue', $buttonUrl);
            
    }
    
    
}
