<?php

// version 2

namespace App\Mailable;


use App\Traits\EmailTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class GeneralMailable extends Mailable
{
    use Queueable, SerializesModels, EmailTrait;
}
