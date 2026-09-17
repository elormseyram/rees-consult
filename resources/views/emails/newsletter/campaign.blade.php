{!! $newsletter->content !!}

<x-mail::footer>
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    [Unsubscribe]({{ route('home') }})
</x-mail::footer>
</x-mail::message>
