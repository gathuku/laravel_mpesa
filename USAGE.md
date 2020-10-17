# Usage
For full Mpesa APIs documentation, read the [official safaricom documentation](https://developer.safaricom.co.ke/docs) or [simplified documentation by Peter Njeru](https://peternjeru.co.ke/safdaraja/ui/).

## Register C2B Urls
This API enables you to register the callback URLs via which you will receive payment notifications for payments to your paybill/till number.

To register Urls ensure `c2b_validate_callback` and `c2b_confirm_callback` are filled in `config/mpesa.php`.
When testing on sandbox you can use [ngrok](https://ngrok.com/) to expose your callbacks to the internet.
Then call `c2bRegisterUrls()` on `Mpesa` facade.

::: tip
> Remember to import `Mpesa facade`
>> `use Mpesa`
:::

```php
$registerUrlsResponse=Mpesa::c2bRegisterUrls()
```

Upon successful urls registration you should get the following response;
```json
{
	"ConversationID": "",
	"OriginatorCoversationID": "",
	"ResponseDescription": "success"
}
```

## C2B API
You can use C2B API to simulate payment from clients and safaricom API. Before simulating you need to have registered your urls using `Register C2B Urls API`.

To use C2B ensure `consumer_key`,`consumer_secret` and `paybill` are set in `config/mpesa.php`.
To simulate you need to pass three parameters to `simulateC2B` function.

1. The `amount` being simulated
2. Test `MSISDN`- from API test credentials
3. Bill `reference`

```php
$simulateResponse=Mpesa::simulateC2B(100, "254708374149", "Testing");
```

Upon successful simulation you will receive a response similar to;
```json
       {
            "ConversationID": "AG_20190115_00007fc37fc3db6e9562",
            "OriginatorCoversationID": "10028-4198443-1",
            "ResponseDescription": "Accept the service request successfully."
        }
```

For you to receive payment confirmation you need to register a route in `routes/api.php` according to the confirmation and validation callbacks you registered.
Then you can use laravel log facade to log the request content in `storage/logs/laravel.log` file.

For example, for the following callback urls you can get validation and confirmation requests using the code below
```
//Validation callback
https://b2d7e6a4.ngrok.io/api/validate?key=ertyuiowwws

//Confirmation callback
https://b2d7e6a4.ngrok.io/api/confirm?key=ertyuiowwws
```
For validation callback;
```php
Route::post('validate', function(Request $request){
    Log::info($request->getContent());
});
```

For confirmation callback;
```php
Route::post('confirm', function(Request $request){
    Log::info($request->getContent());
});
```

You will receive a confirmation similar to the below, to the confirmation url you registered;
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

## B2C API
To use this API you need to call `b2c()` on `Mpesa` facade. This function accept the following parameters
1. `Amount` - Amount of money sent to a consumer
2. `MSISDN` - Phone number of consumer
3. `command_id` -This specifies the type of transaction.There are three allowed values on the API: `SalaryPayment`, `BusinessPayment` or `PromotionPayment`
4. `Remarks` - small decription of the payment being made.

```php
$b2cResponse=Mpesa::b2c(100,'254708374149','PromotionPayment','testing');
```
Upon success you should receive a response similar to the one below;
```json

       {
            "ConversationID": "AG_20190117_00004636fb3ac56655df",
            "OriginatorConversationID": "17503-13504109-1",
            "ResponseCode":"0",
            "ResponseDescription": "Accept the service request successfully."
        }
```

After a successful transaction you will get a callback via the `b2c_result` url you specified in `mpesa.php` config. A sample success callback is below.
```json
 {
    "Result": {
        "ResultType": 0,
        "ResultCode": 0,
        "ResultDesc": "The service request is processed successfully.",
        "OriginatorConversationID": "10030-6237802-1",
        "ConversationID": "AG_20190119_000053c075d4e13cbeae",
        "TransactionID": "NAJ41H7YJQ",
        "ResultParameters": {
            "ResultParameter": [
                {
                    "Key": "TransactionReceipt",
                    "Value": "NAJ41H7YJQ"
                },
                {
                    "Key": "TransactionAmount",
                    "Value": 100
                },
                {
                    "Key": "B2CChargesPaidAccountAvailableFunds",
                    "Value": -495
                },
                {
                    "Key": "B2CRecipientIsRegisteredCustomer",
                    "Value": "Y"
                },
                {
                    "Key": "TransactionCompletedDateTime",
                    "Value": "19.01.2019 17:01:27"
                },
                {
                    "Key": "ReceiverPartyPublicName",
                    "Value": "254708374149 - John Doe"
                },
                {
                    "Key": "B2CWorkingAccountAvailableFunds",
                    "Value": 600000
                },
                {
                    "Key": "B2CUtilityAccountAvailableFunds",
                    "Value": 235
                }
            ]
        },
        "ReferenceData": {
            "ReferenceItem": {
                "Key": "QueueTimeoutURL",
                "Value": "https://internalsandbox.safaricom.co.ke/mpesa/b2cresults/v1/submit"
            }
        }
    }
}
```

## Lipa na Mpesa Online
The following must be filled in `config/mpesa.php`
- 'consumer_key' => ''
- 'consumer_secret' => ''
- 'lipa_na_mpesa' => ''
- 'lipa_na_mpesa_passkey' => ''
- 'lnmocallback' => '',

To run this api call `express` function on `Mpesa` Facade. The function requires the following parameters
1. `amount` - Amount of money to charge
2. `msisdn` - Phone Number of debit party
3. `AccountReference` - Payment reference
4. `TransactionDesc`  - a small description of the payment

```php
$expressResponse=Mpesa::express(100,'2547112855','24242524','Testing Payment');
```

Upon success `$expressREsponse` will return a response like below;
```json
{
  "MerchantRequestID": "10029-6178310-1",
  "CheckoutRequestID": "ws_CO_DMZ_291417540_19012019145720246",
  "ResponseCode": "0",
  "ResponseDescription": "Success. Request accepted for processing",
  "CustomerMessage": "Success. Request accepted for processing"
}
```
After success you will get a callback via the `lnmocallback` url you specified in config `mpesa` file.
```json
{
    "Body": {
        "stkCallback": {
            "MerchantRequestID": "5913-662870-1",
            "CheckoutRequestID": "ws_CO_DMZ_224117480_19012019164445976",
            "ResultCode": 0,
            "ResultDesc": "The service request is processed successfully.",
            "CallbackMetadata": {
                "Item": [
                    {
                        "Name": "Amount",
                        "Value": 1
                    },
                    {
                        "Name": "MpesaReceiptNumber",
                        "Value": "NAJ3ABAMIR"
                    },
                    {
                        "Name": "Balance"
                    },
                    {
                        "Name": "TransactionDate",
                        "Value": 20190119164514
                    },
                    {
                        "Name": "PhoneNumber",
                        "Value": 254705112855
                    }
                ]
            }
        }
    }
}
```
