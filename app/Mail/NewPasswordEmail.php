<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class NewPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $password;
    
    public function __construct($userName, $password)
    {
        $this->userName = $userName;
        $this->password = $password;
    }
    

    public function build()
    {
        return $this->view('new_password')
            ->subject('Your New Password');
    }

   
}
