<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $aParameters = [];

    /**
     * Create a new message instance.
     * @param array $aParameters
     */
    public function __construct(array $aParameters)
    {
        $this->aParameters = $aParameters;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build()
    {
        return $this->from($this->aParameters['sender'])
            ->subject($this->aParameters['title'])
            ->view('mail.response')
            ->with($this->aParameters);
    }
}
