# Laravel Mpesa Package

This package help you in integrating your laravel application with Mpesa daraja APIS. The package eliminate all the hastles and let you conntrate with what is important.

The package will help you integrating the following APIs, available in mpesa daraja

- C2B (consumer to business)
- B2C (business to cunsumers)
- Lipa na mpesa online
- Reversal
- Transaction status
- Account balance

 
## Installation
You can install this awesome package via composer

```
composer require gathuku\laravel_mpesa

```
The package will install automatically to your application


## Usage

### Register C2B Urls
This API enables you to register the callback URLs via which you shall receive payment notifications for payments to your paybill/till number. Read more on [official safaricom documentation](https://developer.safaricom.co.ke/docs) or on [ a simplified documentation by Peter Njeru](https://peternjeru.co.ke/safdaraja/ui/)

To register Urls call this method using Mpesa facade from your controller

```php
$registerUrlsResponse=Mpesa::c2bRegisterUrls()
```

Upon successful registering of urls you should get the following response


### C2B API
You can use 2B API to simulate payment from clients and safaricom API

To use C2B you need to pass three parameters to simulate C2B function

1. The amount being simulated
2. Test MSISDN- form API test credentials
3. Bill reference

```php
$simulateResponse=Mpesa::simulateC2B(100, "254708374149", "Testing");

```
Upon successful simulation you will receive this responce

```json
       {
            "ConversationID": "AG_20190115_00007fc37fc3db6e9562",
            "OriginatorCoversationID": "10028-4198443-1",
            "ResponseDescription": "Accept the service request successfully."
        }

```
You will also receive a transaction confirmation from your the confirmation you registered
```json
       {
            "TransactionType": "Pay Bill",
            "TransID": "NAF81H7Y72",
            "TransTime": "20190115234802",
            "TransAmount": "100.00",
            "BusinessShortCode": "601380",
            "BillRefNumber": "Testing",
            "InvoiceNumber": "",
            "OrgAccountBalance": "900.00",
            "ThirdPartyTransID": "",
            "MSISDN": "254708374149",
            "FirstName": "John",
            "MiddleName": "J.",
            "LastName": "Doe"
        }
```

