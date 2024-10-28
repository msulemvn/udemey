<!-- resources/views/emails/password.blade.php -->

<h3>Hello, {{ $data['name'] }}!</h3>
<p>You are receiving this email because we received a password reset request for your account.</p>
<center><a href="{{$data['url']}}" target="_blank"><button>Reset Password</button></a></center>
<p>This password reset link will expire in 60 minutes.
</p></br>
<p>If you did not request a password reset, no further action is required.
</p>
Regards<br>
{{ config('app.name') }} Team

<hr>
<p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <br /> {{$data['url']}}</p>
</hr>