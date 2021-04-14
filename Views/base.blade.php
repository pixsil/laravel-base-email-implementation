@component('mail::message')

{!! $email->text !!}

Thanks,

{{ config('app.name') }}
@endcomponent
