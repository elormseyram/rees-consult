<!DOCTYPE html>
<html>

<head>
    <title>New Contact Message</title>
</head>

<body>
    <h2>New Contact Message Received</h2>
    <p><strong>Name:</strong> {{ $contactMessage->name }}</p>
    <p><strong>Email:</strong> {{ $contactMessage->email }}</p>
    @if ($contactMessage->phone)
        <p><strong>Phone:</strong> {{ $contactMessage->phone }}</p>
    @endif
    <p><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
    <hr>
    <p><strong>Message:</strong></p>
    <p>{{ $contactMessage->message }}</p>
</body>

</html>
