<!-- resources/views/emails/verify.blade.php -->

<h3>Hello, {{ $data['name'] }}!</h3>
<p>You are receiving this email because we received a password reset request for your account.</p>
<center><button href="http://localhost:8000/password/reset/d4094891366c09629dcd5eb5f5d27b79518c7df9df1a0749228c998d641b40e1?email=msulemvn%40gmail.com">Reset Password</button></center>
<p>This password reset link will expire in 60 minutes.
</p></br>
<p>If you did not request a password reset, no further action is required.
</p>
Regards<br>
{{ config('app.name') }} Team

<hr>
If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: http://localhost:8000/password/reset/d4094891366c09629dcd5eb5f5d27b79518c7df9df1a0749228c998d641b40e1?email=msulemvn%40gmail.com
</hr>