<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PaymobController extends Controller
{
    protected $httpClient;
    protected $apiKey;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://accept.paymob.com/api/',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('services.paymob.api_key'),
            ],
        ]);

        $this->apiKey = config('services.paymob.api_key');
    }

    public function initiatePayment(request $orderData)
    {
        $response = $this->httpClient->post('paymob/initiate-payment', [
            'json' => [
                'auth_token' => $this->apiKey,
                'delivery_needed' => 'false',
                'amount_cents' => $orderData['amount'] * 100,
                'currency' => 'EGP',
                'items' => [],
                'merchant_order_id' => $orderData['order_id'],
            ],
        ]);
    
        $responseData = json_decode($response->getBody()->getContents(), true);
    
        return $responseData['token'];
    }
    
    public function confirmPayment($paymentToken, $orderID)
    {
        $response = $this->httpClient->post('paymob/confirm-payment', [
            'json' => [
                'auth_token' => $this->apiKey,
                'delivery_needed' => 'false',
                'amount_cents' => $orderID['amount'] * 100,
                'currency' => 'EGP',
                'payment_token' => $paymentToken,
            ],
        ]);
    
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData;
        } else {
            throw new \Exception('Error processing payment: ' . $response->getBody()->getContents());
        }
    }
}
