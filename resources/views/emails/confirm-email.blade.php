@component('mail::message')
# One Last Step

We just need you to confirm your email address and that you be all.
@component('mail::button', ['url' => url('/register/confirm?token='.$user->confirmation_token)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
