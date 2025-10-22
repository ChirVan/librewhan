<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FormattedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $bodyHtml;

    /**
     * $bodyHtml may contain HTML. Pass plain text wrapped in <p> if needed.
     */
    public function __construct(string $title, string $bodyHtml)
    {
        $this->title = $title;
        $this->bodyHtml = $bodyHtml;
    }

    public function build()
    {
        return $this->subject($this->title)
                    ->view('emails.formatted', [
                        'title' => $this->title,
                        'bodyHtml' => $this->bodyHtml,
                    ]);
    }
}