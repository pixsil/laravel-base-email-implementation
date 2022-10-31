<?php

// version 2.1

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    //
    protected $fillable = ['identifier', 'mailable', 'subject', 'text', 'template'];
}
