<x-mail::message>
    # Welcome to Rees Consult!

    Thank you for subscribing to our newsletter. We are excited to have you on board!
    You will now receive updates on the latest scholarships, study abroad opportunities, and more.

    <x-mail::button :url="route('home')">
        Visit Website
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
