# Laravel Mpesa Package

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/414b845bd4ec44a1894d4f7a7499c227)](https://app.codacy.com/app/gathuku/laravel_mpesa?utm_source=github.com&utm_medium=referral&utm_content=gathuku/laravel_mpesa&utm_campaign=Badge_Grade_Dashboard)
[![Latest Version](https://img.shields.io/github/release/gathuku/laravel_mpesa.svg?style=flat-square)](https://github.com/gathuku/laravel_mpesa/releases)
[![Issues](https://img.shields.io/github/issues/gathuku/laravel_mpesa.svg?style=flat-square)](https://github.com/gathuku/laravel_mpesa/issues)
[![Total Downloads](https://img.shields.io/packagist/dt/gathuku/laravelmpesa.svg?style=flat-square)](https://packagist.org/packages/gathuku/laravelmpesa)
[![Twiter](https://img.shields.io/twitter/url/https/github.com/gathuku/laravel_mpesa.svg?style=social?style=social)](https://twitter.com/Gathukumose)


This package helps you integrate your laravel application with Mpesa daraja APIs. The package eliminates (almost)all the hassles and lets you concentrate on what is important.

The package will help you integrate with the following APIs, available on mpesa daraja;

- C2B (consumer to business)
- B2C (business to cunsumer)
- Lipa na mpesa online(Mpesa Express)
- Reversal
- Transaction status
- Account balance

## Documentation
You are looking at it. But we've also got [beautiful, fully navigable docs](https://beyode.co.ke/mpesa/).

## Installation
You can install this awesome package via composer

```sh
composer require gathuku/laravelmpesa
```
If you're using Laravel >=5.5, this is all you have to do.

Should you still be on version 5.4 of Laravel, the final steps for you are to add the service provider of the package and alias the package. To do this open your `config/app.php` file.

Add a new line to the `providers` array:
```php
 Gathuku\Mpesa\MpesaServiceProvider::class,
```
And optionally add a new line to the `aliases` array:
```php
'Mpesa' => Gathuku\Mpesa\Facades\Mpesa::class,
```

### Happy Coding :tada: :100:

## Configuration
Next, after the package has been installed run;
```
php artisan mpesa:install
```
or 

```sh
php artisan vendor:publish
```
This will help in publishing `config/mpesa.php` file.
This *mpesa config* file is where you will add all configurations for Mpesa APIs.
This includes the environment your application is running in(sandbox or production), callback URLs and  required credentials.
You will obtain credentials from your `app` on Safaricom's [developer portal](https://developer.safaricom.co.ke).

```php
<?php

return [
    //Specify the environment mpesa is running, sandbox or production
    'mpesa_env' => 'sandbox',
    /*-----------------------------------------
    |The App consumer key
    |------------------------------------------
    */
    'consumer_key'   => 'aR7R09zePq0OSfOttvuQDrfdM4n37i0C',  

    /*-----------------------------------------
    |The App consumer Secret
    |------------------------------------------
    */                     
    'consumer_secret' => 'F9AebI6azDlRjLiR',     

    /*-----------------------------------------
    |The paybill number
    |------------------------------------------
    */
    'paybill'         => 601380,

    /*-----------------------------------------
    |The Lipa Na Mpesa Online shortcode
    |------------------------------------------
    */
    'lipa_na_mpesa'  => '174379',
];
```

For production you need to replace with production credentials.

For security reasons you may want to define your API credentials in `env` file. For example;
```php
  'consumer_key'   => env('CONSUMER_KEY'),
```

### Usage
Full usage and examples in [USAGE.md](./USAGE.md)

### Contributing
Thank you for considering contributing to `laravelmpesa`. Pull requests and issues welcome.

Be sure to read through [CONTRIBUTING.md](./CONTRIBUTING.md) and check open issues and PRs before continuing.

### Security Vulnerabilities
If you discover a security vulnerability within `laravelmpesa`, please send an e-mail to Moses Gathuku via gathukumoses12@gmail.com. All security vulnerabilities will be promptly addressed.

### License
The `laravelmpesa` package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT)
