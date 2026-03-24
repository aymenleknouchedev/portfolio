<x-mail::message>
# New Contact Message

**From:** {{ $data['name'] }} ({{ $data['email'] }})

**Subject:** {{ $data['subject'] }}

---

{{ $data['message'] }}

<x-mail::button :url="url('/admin/contact-messages')">
View in Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
