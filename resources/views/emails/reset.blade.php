<!-- resources/views/emails/password.blade.php -->

<h3>Hello, {{ $data['name'] }}!</h3>
<p>You have successfully changed your {{ config('app.name') }} account password. If you did not make this request, please reset the passwords of your email address and {{ config('app.name') }} account.</p> </br>
Regards <br>
{{ config('app.name') }} Team