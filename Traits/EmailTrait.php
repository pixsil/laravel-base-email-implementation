<?php

// version 10 added a button functionality
// version 9 fixed folder for emails
// version 8 added parameter for extra data
// version 7 fixed that the classes could be used inside the html
// version 6

namespace App\Traits;

use App\Models\Email;
use Illuminate\Support\Facades\View;

trait EmailTrait
{
    public $email;
    public $placeholder_data = [];
    private $template;
    private $object_arr = [];
    private $extra_data_arr = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($identifier, $object_arr = [], $extra_data_arr = [])
    {
        // set email
        $this->setObjectsAsPlaceholders($object_arr);
        $this->setExtraDataAsPlaceholders($extra_data_arr);
        $this->setupEmail($identifier, $object_arr);
    }

    /**
     *
     */
    private function setObjectsAsPlaceholders($object_arr)
    {
        // set available to send with the build command "->with()"
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
    }
    /**
     *
     */
    private function setExtraDataAsPlaceholders($extra_data_arr)
    {
        // set available to send with the build command "->with()"
        $this->extra_data_arr = $extra_data_arr;

        // install objects so you can use them inside the email
        foreach ($extra_data_arr as $key => $value) {

            //
            $this->placeholder_data['{{'. $key .'}}'] = $value;
        }
    }

    /**
     *
     */
    private function setupEmail($identifier, $object_arr)
    {
        // get the body text
        $this->email = Email::firstOrCreate(
            ['identifier' => $identifier],
            [
                'subject' => class_basename(static::class),
                'text' => implode(', ', array_keys($this->placeholder_data ?? [])),
                'template' => 'mail.general',
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
        $this->template = $this->email->template ?: 'mail.general';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->setTemplate();

        // replace buttons
        // [Click here](https://example.com){button}
        $this->email->text = preg_replace_callback('/\[(.*?)\]\((.*?)\)\{button\}/', function ($matches) {
            return View::make('vendor.mail.html.button', ['url' => $matches[2] ?? ''])
                ->with('slot', $matches[1] ?? '')
                ->render();
        }, $this->email->text);

        return $this->subject($this->email->subject)
            ->markdown($this->template)
            ->with($this->object_arr)
            ->with($this->extra_data_arr);
    }
}
