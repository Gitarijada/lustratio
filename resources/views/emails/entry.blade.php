@component('mail::message')
# Introduction to shooting event

This is The body of your message.
For all of Gang of yours ;-)

@component('mail::button', ['url' => 'http://localhost/MTVP/public/'])
Visit site (portal)
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
