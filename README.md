# Laravel base email implementation


## What is it?

You can use these files to start real quick with a base email implementation. The implementation got a new nice features out of the box:

* It is possible to use placeholders from a given object
* All the email texts and titels and settings are editable form the cms
* Easy to extend with more emails
* Define different layouts and apply them in the database record
* Database settings automatically generated

## Donate

Find this project useful? You can support me with a Paypal donation:

[Make Paypal Donation](https://www.paypal.com/donate/?hosted_button_id=2XCS6R3CTC5BA)

## Installation

For a quick install, run this from your project root:
```bash
mkdir app/Models
wget -O app/Models/Email.php https://raw.githubusercontent.com/pixsil/laravel-base-email-implementation/main/Models/Email.php

wget -O database/migrations/`date '+%Y_%m_%d_%H%M%S'`_create_emails_table.php https://raw.githubusercontent.com/pixsil/laravel-base-email-implementation/main/Migrations/create_emails_table.php
php artisan migrate

mkdir app/Traits
wget -O app/Traits/EmailTrait.php https://raw.githubusercontent.com/pixsil/laravel-base-email-implementation/main/Traits/EmailTrait.php

mkdir app/Mailable
wget -O app/Mailable/ExampleMailable.php https://raw.githubusercontent.com/pixsil/laravel-base-email-implementation/main/Mailable/ExampleMessageMailable.php

mkdir resources/views/emails
wget -O resources/views/emails/base.blade.php https://raw.githubusercontent.com/pixsil/laravel-base-email-implementation/main/Views/base.blade.php
```

Add this to your app.js
```bash
require('./tools/vue-form/vue-form.js');
require('./tools/vue-form/vue-error.js');
```

## Usage

In controller:
```php
// send email
Mail::to($user)->send(new ExampleMessage($assignment->toArray()));
```
