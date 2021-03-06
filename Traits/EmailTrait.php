<?php

// version 7 fixed that the classes could be used inside the html
// version 6

namespace App\Traits;

use App\Models\Email;

trait EmailTrait
{
    public $email;
    public $placeholder_data = [];
    private $template;
    private $object_arr = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($identifier, $object_arr = [])
    {
        // set email
        $this->setupEmail($identifier, $object_arr);
    }

    /**
     * get storage path
     */
    public function setupEmail($identifier, $object_arr)
    {
        // guard always extent
        if (static::class == 'App\Mail\BaseMailable') {
            abort(403);
        }

        // set available to send with the build command
        $this->object_arr = $object_arr;

        // install objects so you can use them inside the email
        foreach ($object_arr as $key => $object) {

            // if a specific key is set use this as name
            $object_name = is_string($key) ? $key : $object->getTable();

            // set as object
            // this does not work, objects must be defined as public in the class file
            // so doing this now with the with() function on the build
            // $this->$object_name = $object;

            // also fill an array with all the possible markers
            foreach ($object->getAttributes() as $column => $attribute) {
                $this->placeholder_data['{{'. $object_name .'.'. $column .'}}'] = $attribute;
            }
        }

        // // install placeholders
        // foreach ($this->placeholders as $placeholderName => $placeholderValue) {
        //     list($object, $key) = explode('.',$placeholderValue);
        //     $this->placeholder_data[$placeholderName] = $this->$object->$key;
        // }

        // get the body text
        $this->email = Email::firstOrCreate(
            ['identifier' => $identifier],
            [
                'subject' => class_basename(static::class),
                'text' => implode(', ', array_keys($this->placeholder_data ?? [])),
                'template' => 'emails.general',
            ]
        );

        // if we have placeholders to replace
        if ($this->placeholder_data) {
            $this->email->subject = $this->replace_placeholders($this->email->subject);
            $this->email->text = $this->replace_placeholders($this->email->text);
        }
    }

    /**
     *
     */
    public function merge_to_placeholder_arr($data_arr)
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
        $text = strtr($text, $this->placeholder_data);

        return $text;
    }

    /**
     * Pre-build the message.
     *
     * @return $this
     */
    private function setTemplate()
    {
        $this->template = $this->email->template ?: 'emails.general';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setTemplate();

        return $this->subject($this->email->subject)
            ->markdown($this->template)
            ->with($this->object_arr);
    }
}
