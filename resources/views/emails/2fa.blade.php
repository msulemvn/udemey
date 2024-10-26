<!-- resources/views/emails/2fa.blade.php -->

<h3>Hello, {{ $data['name'] }}!</h3>
<p>Enter this code in your {{ config('app.name') }} app to log into your account:</p>
<center>
    <h2>356097</h2>
</center>
</p></br>
<p> If you did not request this code, this may be because someone accidentally typed in your username or email address.</p>
Regards<br>
{{ config('app.name') }} Team

<hr>
Please don't share this code with anyone, and only use it inside {{ config('app.name') }}. We recommend that you verify your contact information and set up two-factor authentication directly in the {{ config('app.name') }} app to make your account more secure. Only people who know your {{ config('app.name') }} password or use the code in this email can log into your account.</hr>