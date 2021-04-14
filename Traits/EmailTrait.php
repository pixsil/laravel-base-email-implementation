<?php

// version 3

namespace App\Traits;

use App\Models\Email;

trait EmailTrait
{
    public $email;
    private $placeholder_arr;


    /**
     * get storage path
     */
    public function setupEmail()
    {
        // get the body text
        $this->email = Email::firstOrCreate(
            ['mailable' => class_basename(static::class)],
            [
                'subject' => class_basename(static::class),
                'text' => implode(', ', array_keys($this->placeholder_arr ?? [])),
                'template' => 'emails.base',
            ]
        );


        // replace placeholders
        if ($this->placeholder_arr) {
            $this->email->text = $this->replace_placeholders($this->email->text);
        }
    }


    /**
     * get storage path
     */
    public function to_placeholder_arr($data_arr)
    {
        // get the body text
        foreach ($data_arr as $key => $data) {


            // write new
            $data_arr['{{'. $key .'}}'] = $data;


            // unset old
            unset($data_arr[$key]);
        }


        return $data_arr;
    }


    /**
     * get storage path
     */
    public function replace_placeholders($text)
    {
        // get the body text
        $text = strtr($text, $this->placeholder_arr);


        return $text;
    }
}


/*
        // set placeholders
        $this->placeholder_arr = [
        ];


        // set email
        $this->setEmail();
*/
