<?php

// version 4

namespace App\Traits;

use App\Models\Email;

trait EmailTrait
{
    public $email;
    public $placeholderData = [];
    private $template;

    /**
     * get storage path
     */
    public function setupEmail($compactObjectArr)
    {
        // guard always extent
        if (static::class == 'App\Mail\BaseMailable') {
            abort(403);
        }

        // install objects
        foreach ($compactObjectArr as $name => $object) {
            $this->$name = $object;
        }

        // install placeholders
        foreach ($this->placeholders as $placeholderName => $placeholderValue) {
            list($object, $key) = explode('.',$placeholderValue);
            $this->placeholderData[$placeholderName] = $this->$object->$key;
        }

        // get the body text
        $this->email = Email::firstOrCreate(
            ['mailable' => class_basename(static::class)],
            [
                'subject' => class_basename(static::class),
                'text' => implode(', ', array_keys($this->placeholder_arr ?? [])),
                'template' => 'emails.base',
            ]
        );

        // if we have placeholders to replace
        if ($this->placeholderData) {
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
        $text = strtr($text, $this->placeholderData);

        return $text;
    }

    /**
     * Pre-build the message.
     *
     * @return $this
     */
    private function setTemplate()
    {
        $this->template = $this->email->template ?: 'emails.base';
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

    /*
            // set placeholders
            $this->placeholder_arr = [
            ];
            // set email
            $this->setEmail();
    */
