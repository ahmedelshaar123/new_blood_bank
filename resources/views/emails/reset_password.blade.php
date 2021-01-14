@component('mail::message')
    # Reset Password

    Your Pin code Is {{$code}}

    @component('mail::button', ['url' => ''])
        Reset Password
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
