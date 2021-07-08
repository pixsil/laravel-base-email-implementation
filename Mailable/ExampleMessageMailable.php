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

    // if you like to use the variables in the email
    // dont have to assign them anymore
    public $order;
    
    private $placeholders = ['{{order_id}}' => 'order.id'];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($compactObjectArr)
    {
        // set email
        $this->setupEmail($compactObjectArr);
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setTemplate();

        return $this->subject($this->email->subject)->markdown($this->email->template);
    }
}
