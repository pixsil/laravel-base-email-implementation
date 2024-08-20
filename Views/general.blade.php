<x-mail::message>

{!! $email->text !!}

@if(isset($button))

<x-mail::button :url="$button['url']">

{{ $button['text'] }}

</x-mail::button>

@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
