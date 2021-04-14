<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    //
    public $fillable = ['mailable', 'subject', 'text', 'template'];
}
