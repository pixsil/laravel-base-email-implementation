<?php


namespace App\Mailable;


use App\Traits\EmailTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SiteFeedbackCreated extends Mailable
{
    use Queueable, SerializesModels, EmailTrait;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data_arr = [])
    {
        // set placeholders
        $this->placeholder_arr = $this->to_placeholder_arr($data_arr);


        // set email
        $this->setupEmail();
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setTemplate();

        return $this->subject($this->email->subject)->markdown($this->template);
    }
}
