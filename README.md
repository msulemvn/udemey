# udemey
udemey is a udemy clone
  
## Project Configuration
### Composer
```
composer update
composer install
```
### .env Setup
```
DB_DATABASE=udemey
QUEUE_CONNECTION=database
```
SMTP config
- enable 2F Auth
- setup app password at </br>
https://myaccount.google.com/apppasswords
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=<gmail>
MAIL_PASSWORD=<app password>
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=<gmail>
MAIL_FROM_NAME="${APP_NAME}"
```
### Timezone
In App\config\app.php project timezone is set to 
```
'timezone' => 'Asia/Karachi',
```
###  Install JWT Auth Package 
```
composer require php-open-source-saver/jwt-auth
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
php artisan key:generate
```
### Configure Auth Guard
Inside the config/auth.php file you will need to make a few changes to configure Laravel to use the jwt guard to power your application authentication.
Make the following changes to the file:
In App/config/auth.php
```
'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],


    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
                'driver' => 'jwt',
                'provider' => 'users',
        ],

    ],

```
### Google-2FA
Authentication package, compatible with Google Authenticator. Follow the instructions to setup </br>
<a>https://github.com/antonioribeiro/google2fa-laravel</a>

### Spatie
This package allows you to manage user permissions and roles in a database.
Refer to official documentation for setup.
</br>
<a href="https://spatie.be/docs/laravel-permission/v6/installation-laravel"  target="_blank">Spatie for Laravel</a>

### Commands to run project
```
php artisan migrate --seed
php artisan serve
php artisan queue:work
php artisan schedule:work
```
