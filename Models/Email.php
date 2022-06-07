<?php

// version 2

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    //
    public $fillable = ['identifier', 'mailable', 'subject', 'text', 'template'];
}
