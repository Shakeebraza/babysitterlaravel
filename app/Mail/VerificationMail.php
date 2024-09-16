<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $first_name;
    private $email_verification_code;
    private $language;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $email_verification_code, $language = null)
    {
        $this->first_name = $first_name;
        $this->email_verification_code = $email_verification_code;
        $this->language = $language == null ? app()->getLocale() : $language;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@babysitter-app.com', 'Babysitter-App')
            ->subject(trans('messages.verification_code_title'))
            ->view('mail_template/verification_mail_html') // Pfad zur HTML-Ansicht
            ->text('mail_template/verification_mail_text') // Pfad zur Plain-Text-Ansicht
            ->with([
                'preheaderText' => trans('messages.verification_code_preheader'),
                'firstName' => $this->first_name,
                'emailVerificationCode' => $this->email_verification_code,
                'language' => $this->language,
            ]);
    }
}
