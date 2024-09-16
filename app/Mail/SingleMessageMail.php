<?php

namespace App\Mail;

use App\Models\UserRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SingleMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    private $first_name;
    private $messageBody;
    private $request;
    private $language;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($first_name, $subject, $messageBody, UserRequest $request = null, $language = null)
    {
        $this->first_name = $first_name;
        $this->subject($subject);
        $this->messageBody = $messageBody;
        $this->request = $request;
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

        $email = $this->from('no-reply@babysitter-app.com', 'Babysitter-App')
            ->replyTo('contact@babysitter-app.com', 'Babysitter-App')
            ->view('mail_template/single_message_mail_html')
            ->text('mail_template/single_message_mail_text')
            ->with([
                'preheaderText' => $this->messageBody,
                'firstName' => $this->first_name,
                'language' => $this->language,
                'messageBody' => $this->messageBody,
                'request' => $this->request,
            ]);

        return $email;
    }
}
