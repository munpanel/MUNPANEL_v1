<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

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
                    //->text('emailPlain')
                    //->attach('/var/www/munpanel/app/BJMUNSS2017academicteam.xlsx', ['as' => 'BJMUNSS 2017 学术团队名单.xlsx','mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',])
                    ->with([
                        'id' => $this->email->id,
                        'title' => $this->email->title,
                        'content' => $this->email->content,
                        'plainContent' => $this->email->plainContent,
                        'sender' => $this->email->sender,
                        'receiver' => json_decode($this->email->receiver),
                    ]);
    }
}
