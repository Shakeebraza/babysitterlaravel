<?php

namespace App\Mail;

use App\Models\UserRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    private $first_name;
    private $requests;
    private $language;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $requests, $language = null)
    {
        $this->first_name = $first_name;
        $this->requests = $requests;
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

        return $this->from('no-reply@babysitter-app.com', 'Babysitter-App')
            ->replyTo('contact@babysitter-app.com', 'Babysitter-App')
            ->subject(trans('emails.new_subscription_subject'))
            ->view('mail_template/subscription_mail_html')
            ->text('mail_template/subscription_mail_text')
            ->with([
                'firstName' => $this->first_name,
                'language' => $this->language,
                'requests' => $this->requests,
            ]);
    }
}
