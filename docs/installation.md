## Installation
You can install this awesome package via composer

```
composer require gathuku/laravelmpesa

```
If you're using Laravel >=5.5, this is all you have to do.

Should you still be on version 5.4 of Laravel, the final steps for you are to add the service provider of the package and alias the package. To do this open your `config/app.php` file.

Add a new line to the `providers` array:
```
 Gathuku\Mpesa\MpesaServiceProvider::class,
```
And optionally add a new line to the `aliases` array:
```
'Mpesa' => Gathuku\Mpesa\Facades\Mpesa::class,
```

### Happy Coding :tada: :100:
