## Configuration
Next, After the package have been installed run
```
php artisan vendor:publish
```
This will help in publishing `config/mpesa.php` file. . From the mpesa config file this where you will define if your application is running in sandbox or production. If your application is running on sandbox you  will define `'mpesa_status' => 'sandbox',`
You will continue filling your test credentials from your application in [developers portal ](developers.safaricom.co.ke)


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

For security reason you may need to define your API in `env` file. For example
```php
  'consumer_key'   => env('CONSUMER_KEY'),
```
