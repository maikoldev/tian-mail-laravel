<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = file_get_contents(public_path($this->data['certificate_url']));
        $photo = file_get_contents(public_path($this->data['photo_url']));

        return $this->attachData($pdf, $this->data['pdf_name'])
            ->attachData($photo, $this->data['photo_name'])
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->markdown('emails.certificate', ['data' => $this->data])
            ->subject(env('MAIL_SUBJECT'));
    }
}
