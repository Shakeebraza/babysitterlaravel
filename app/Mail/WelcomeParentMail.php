<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeParentMail extends Mailable
{
    use Queueable, SerializesModels;

    private $first_name;
    private $language;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $language = null)
    {
        $this->first_name = $first_name;
        $this->language = $language == null ? app()->getLocale() : $language;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        app()->setLocale($this->language);

        return $this->from('no-reply@babysitter-app.com', 'Raffael - Babysitter-App')
            ->replyTo('contact@babysitter-app.com', 'Raffael - Babysitter-App')
            ->subject(__('emails.welcome_parent_subject'))
            ->view('mail_template/welcome_parent_mail_html')
            ->text('mail_template/welcome_parent_mail_text')
            ->with([
                'preheaderText' => trans('emails.welcome_parent_preheader'),
                'firstName' => $this->first_name,
                'language' => $this->language,
            ]);
    }
}
