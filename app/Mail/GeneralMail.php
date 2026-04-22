<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;

    public string $title;

    public string $messageText;

    public ?string $buttonText;

    public ?string $buttonUrl;

    public function __construct(
        string $subjectText,
        string $title,
        string $messageText,
        ?string $buttonText = null,
        ?string $buttonUrl = null
    ) {
        $this->subjectText = $subjectText;
        $this->title = $title;
        $this->messageText = $messageText;
        $this->buttonText = $buttonText;
        $this->buttonUrl = $buttonUrl;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->view('emails.general');
    }
}
