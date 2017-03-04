<?php

namespace App\Mail;

use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->email->title)
                    ->view('emailTemplate')
                    ->with([
                        'id' => $this->email->id,
                        'title' => $this->email->title,
                        'content' => $this->email->content,
                        'sender' => $this->email->sender,
                        'receiver' => json_decode($this->email->receiver),
                    ]);
    }
}
