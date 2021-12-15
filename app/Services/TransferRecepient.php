<?php

namespace App\Services;

use App\Services\InitiateTransfer;

class TransferRecepient
{

    protected $initiateTransfer;

    public function __construct(InitiateTransfer $initiateTransfer){
        $this->initiateTransfer = $initiateTransfer;
    }

    /*
    *   This generate the recepient code to receive the transaction
    */
    public function execute($response, $data)
    {  
        $url = "https://api.paystack.co/transferrecipient";
        $bankCode = $data['bank_code'];
        $fields = [
          "type" => "nuban",
          "name" => $response['data']['account_name'],
          "description" => "Enjoy Yourself",
          "account_number" => $response['data']['account_number'],
          "bank_code" => $bankCode,
          "currency" => "NGN"
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $ch = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          "Authorization: Bearer ".('sk_test_17e8bb75cbab4ce27af543711f0cb71ad18f1677'),
          "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($ch);
        
        $response = json_decode($result, true);    
        
        //  dd($response);

        // Save the recipient_code key to the user making this request

        return $this->initiateTransfer->execute($response, $data);
    }

}