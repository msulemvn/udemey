<!-- resources/views/emails/password.blade.php -->

# Reset Password
<p>Hello, {{ $data['name'] }}!</p>
<p>{{ $data['message'] }}</p>
Thanks,<br>
{{ config('app.name') }}