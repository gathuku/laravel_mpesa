<?php

namespace Gathuku\Mpesa;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class Mpesa
{



    /**
     * The common part of the MPesa API endpoints
     * @var string $base_url
     */
    private $base_url;
    /**
     * The consumer key
     * @var string $consumer_key
     */
    public $consumer_key;
    /**
     * The consumer key secret
     * @var string $consumer_secret
     */
    public $consumer_secret;
    /**
     * The MPesa Paybill number
     * @var int $paybill
     */
    public $paybill;
    /**
     * The Lipa Na MPesa paybill number
     * @var int $lipa_na_mpesa
     */
    public $lipa_na_mpesa;
    /**
     * The Lipa Na MPesa paybill number SAG Key
     * @var string $lipa_na_mpesa_key
     */
    public $lipa_na_mpesa_key;
    /**
     * The Mpesa portal Username
     * @var string $initiator_username
     */
    public $initiator_username;
    /**
     * The Mpesa portal Password
     * @var string $initiator_password
     */
    public $initiator_password;
    /**
     * The Callback common part of the URL eg "https://domain.com/callbacks/"
     * @var string $initiator_password
     */
    private $callback_baseurl;
    /**
     * The test phone number provided by safaricom. For developers
     * @var string $test_msisdn
     */
    private $test_msisdn;
    /**
     * The signed API credentials
     * @var string $cred
     */
    private $cred;
    private $access_token;

    /*Callbacks*/
    public $bctimeout;
    public $bcresult;
    public $bbtimeout;
    public $bbresult;
    public $baltimeout;
    public $balresult;
    public $statustimeout;
    public $statusresult;
    public $reversetimeout;
    public $reverseresult;
    public $cbvalidate;
    public $cbconfirm;
    public $lnmocallback;
    /**
     * Construct method
     *
     * Initializes the class with an array of API values.
     *
     * @param array $config
     * @return void
     * @throws exception if the values array is not valid
     */



    public function __construct()
    {
        if (config('mpesa.mpesa_env')=='sandbox') {
            $this->base_url = 'https://sandbox.safaricom.co.ke';
        } else {
            $this->base_url = 'https://api.safaricom.co.ke';
        }
        //Base URL for the API endpoints. This is basically the 'common' part of the API endpoints
         $this->consumer_key = config('mpesa.consumer_key'); 	//App Key. Get it at https://developer.safaricom.co.ke
         $this->consumer_secret = config('mpesa.consumer_secret'); 					//App Secret Key. Get it at https://developer.safaricom.co.ke
         $this->paybill =config('mpesa.paybill'); 									//The paybill/till/lipa na mpesa number
         $this->lipa_na_mpesa = config('mpesa.lipa_na_mpesa');								//Lipa Na Mpesa online checkout
         $this->lipa_na_mpesa_key = config('mpesa.lipa_na_mpesa_passkey');	//Lipa Na Mpesa online checkout password
         $this->initiator_username = config('mpesa.initiator_username'); 					//Initiator Username. I dont where how to get this.
         $this->initiator_password = config('mpesa.initiator_password'); 				//Initiator password. I dont know where to get this either.

         $this->callback_baseurl = 'https://91c77dd6.ngrok.io/api/callback';
        $this->lnmocallback = config('mpesa.lnmocallback');
        $this->test_msisdn = config('mpesa.test_msisdn');
        // c2b the urls
        $this->cbvalidate=config('mpesa.c2b_validate_callback');
        $this->cbconfirm=config('mpesa.c2b_confirm_callback');

        // b2c URLs
        $this->bctimeout=config('mpesa.b2c_timeout');
        $this->bcresult=config('mpesa.b2c_result');

        $this->access_token = $this->getAccessToken(); //Set up access token
    }

    /**
     * Submit Request
     *
     * Handles submission of all API endpoints queries
     *
     * @param string $url The API endpoint URL
     * @param json $data The data to POST to the endpoint $url
     * @return object|boolean Curl response or FALSE on failure
     * @throws exception if the Access Token is not valid
     */

    public function setCred()
    {
        if (config('mpesa.mpesa_env')=='sandbox') {
            $pubkey=File::get(__DIR__.'/cert/sandbox.cer');
        } else {
            $pubkey=File::get(__DIR__.'/cert/production.cer');
        }
        openssl_public_encrypt($this->initiator_password, $output, $pubkey, OPENSSL_PKCS1_PADDING);
        $this->cred = base64_encode($output);
    }


    public function getAccessToken()
    {
        $credentials = base64_encode($this->consumer_key.':'.$this->consumer_secret);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url.'/oauth/v1/generate?grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials, 'Content-Type: application/json'));
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $response = json_decode($response);

        if ($info["http_code"] == 200) {
            $access_token = $response->access_token;
            $this->access_token = $access_token;
            return $access_token;
        } else {
            //throw new Exception("Invalid Consumer key or secret");
            return false;
        }
    }

    private function submit_request($url, $data)
    { // Returns cURL response

        if (isset($this->access_token)) {
            $access_token = $this->access_token;
        } else {
            $access_token = $this->getAccessToken();
        }

        if ($access_token != '' || $access_token !== false) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: Bearer '.$access_token));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } else {
            return false;
        }
    }

    /**
     * Business to Client
     *
     * This method is used to send money to the clients Mpesa account.
     *
     * @param int $amount The amount to send to the client
     * @param int $phone The phone number of the client in the format 2547xxxxxxxx
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function b2c($amount, $phone, $command_id, $remarks)
    {
        //this function will set b2c credentials
        $this->setCred();
        $request_data = array(
            'InitiatorName' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'CommandID' => $command_id,
            'Amount' => $amount,
            'PartyA' => $this->paybill,
            'PartyB' => $phone,
            'Remarks' => $remarks,
            'QueueTimeOutURL' => $this->bctimeout,
            'ResultURL' => $this->bcresult,
            'Occasion' => '' //Optional
        );
        $data = json_encode($request_data);
        $url = $this->base_url.'/mpesa/b2c/v1/paymentrequest';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Business to Business
     *
     * This method is used to send money to other business Mpesa paybills.
     *
     * @param int $amount The amount to send to the business
     * @param int $shortcode The shortcode of the business to send to
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function b2b($amount, $shortcode)
    {
        $request_data = array(
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'CommandID' => 'BusinessToBusinessTransfer',
            'SenderIdentifierType' => 'Shortcode',
            'RecieverIdentifierType' => 'Shortcode',
            'Amount' => 100,
            'PartyA' => $this->paybill,
            'PartyB' => 600000,
            'AccountReference' => 'Bennito',
            'Remarks' => 'This is a test comment or remark',
            'QueueTimeOutURL' => $this->bbtimeout,
            'ResultURL' => $this->bbresult,
        );
        $data = json_encode($request_data);
        $url = $this->base_url.'/mpesa/b2b/v1/paymentrequest';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Client to Business
     *
     * This method is used to register URLs for callbacks when money is sent from the MPesa toolkit menu
     *
     * @param string $confirmURL The local URL that MPesa calls to confirm a payment
     * @param string $ValidationURL The local URL that MPesa calls to validate a payment
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function c2bRegisterUrls()
    {
        $request_data = array(
            'ShortCode' => $this->paybill,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $this->cbconfirm,
            'ValidationURL' => $this->cbvalidate
        );
        $data = json_encode($request_data);
        //header('Content-Type: application/json');

        $url = $this->base_url.'/mpesa/c2b/v1/registerurl';
        $response = $this->submit_request($url, $data);
        //\Log::info($response);
        return $response;
    }

    /**
     * C2B Simulation
     *
     * This method is used to simulate a C2B Transaction to test your ConfirmURL and ValidationURL in the Client to Business method
     *
     * @param int $amount The amount to send to Paybill number
     * @param int $msisdn A dummy Safaricom phone number to simulate transaction in the format 2547xxxxxxxx
     * @param string $ref A reference name for the transaction
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function simulateC2B($amount, $msisdn, $ref)
    {
        $data = array(
            'ShortCode' => $this->paybill,
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'Msisdn' => $msisdn,
            'BillRefNumber' => $ref
        );
        $data = json_encode($data);
        $url = $this->base_url.'/c2b/v1/simulate';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Check Balance
     *
     * Check Paybill balance
     *
     * @return object Curl Response from submit_request, FALSE on failure
     */
    public function check_balance()
    {
        $data = array(
            'CommandID' => 'AccountBalance',
            'PartyA' => $this->paybill,
            'IdentifierType' => '4',
            'Remarks' => 'Remarks or short description',
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'QueueTimeOutURL' => $this->baltimeout,
            'ResultURL' => $this->balresult
        );
        $data = json_encode($data);
        $url = $this->base_url.'/mpesa/accountbalance/v1/query';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Transaction status request
     *
     * This method is used to check a transaction status
     *
     * @param string $transaction ID eg LH7819VXPE
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function status_request($transaction = 'LH7819VXPE')
    {
        $data = array(
            'CommandID' => 'TransactionStatusQuery',
            'PartyA' => $this->paybill,
            'IdentifierType' => 4,
            'Remarks' => 'Testing API',
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'QueueTimeOutURL' => $this->statustimeout,
            'ResultURL' => $this->statusresult,
            'TransactionID' => $transaction,
            'Occassion' => 'Test'
        );
        $data = json_encode($data);
        $url = $this->base_url.'/mpesa/transactionstatus/v1/query';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Transaction Reversal
     *
     * This method is used to reverse a transaction
     *
     * @param int $receiver Phone number in the format 2547xxxxxxxx
     * @param string $trx_id Transaction ID of the Transaction you want to reverse eg LH7819VXPE
     * @param int $amount The amount from the transaction to reverse
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function reverse_transaction($receiver, $trx_id, $amount)
    {
        $data = array(
            'CommandID' => 'TransactionReversal',
            'ReceiverParty' => $this->test_msisdn,
            'RecieverIdentifierType' => 1, //1=MSISDN, 2=Till_Number, 4=Shortcode
            'Remarks' => 'Testing',
            'Amount' => $amount,
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'QueueTimeOutURL' => $this->reversetimeout,
            'ResultURL' => $this->reverseresult,
            'TransactionID' => 'LIE81C8EFI'
        );
        $data = json_encode($data);
        $url = $this->base_url.'/mpesa/reversal/v1/request';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /*********************************************************************
     *
     * 	LNMO APIs
     *
     * *******************************************************************/

    public function express($amount, $phone, $ref = "Payment", $desc="Payment")
    {
        if (!is_numeric($amount) || $amount < 1 || !is_numeric($phone)) {
            throw new Exception("Invalid amount and/or phone number. Amount should be 10 or more, phone number should be in the format 254xxxxxxxx");
            return false;
        }
        $timestamp = date('YmdHis');
        $passwd = base64_encode($this->lipa_na_mpesa.$this->lipa_na_mpesa_key.$timestamp);
        $data = array(
            'BusinessShortCode' => $this->lipa_na_mpesa,
            'Password' => $passwd,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->lipa_na_mpesa,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->lnmocallback,
            'AccountReference' => $ref,
            'TransactionDesc' => $desc,
        );
        $data = json_encode($data);
        $url = $this->base_url.'/mpesa/stkpush/v1/processrequest';
        $response = $this->submit_request($url, $data);

        if (isset($response)) {
            return $response;
        } else {
            return false;
        }
        // $result = json_decode($response);
        // if(isset($result) && isset($result->CheckoutRequestID)){
        // 	$c_id = $result->CheckoutRequestID;
        // 	return $this->lnmo_query($c_id);
        // }else{
        // 	return FALSE;
        // }
    }

    private function lnmoQuery($checkoutRequestID = null)
    {
        $timestamp = date('YmdHis');
        $passwd = base64_encode($this->lipa_na_mpesa.$this->lipa_na_mpesa_key.$timestamp);

        if ($checkoutRequestID == null || $checkoutRequestID == '') {
            //throw new Exception("Checkout Request ID cannot be null");
            return false;
        }

        $data = array(
            'BusinessShortCode' => $this->lipa_na_mpesa,
            'Password' => $passwd,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestID
        );
        $data = json_encode($data);
        $url = $this->base_url.'/mpesa/stkpushquery/v1/query';
        $response = $this->submit_request($url, $data);
        return $response;
    }
}
