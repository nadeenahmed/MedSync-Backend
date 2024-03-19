<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymobService;

class PaymobController extends Controller
{
    protected $payMobService;

    public function __construct(PayMobService $payMobService)
    {
        $this->payMobService = $payMobService;
    }

    public function initiatePayment(Request $request)
    {
        $requestData = $request->validate([
        ]);

     
        $paymentToken = $this->payMobService->initiatePayment($requestData);

        return response()->json(['payment_token' => $paymentToken]);
    }

    public function confirmPayment(Request $request)
    {
      
        $requestData = $request->validate([
            
        ]);

        
        $orderID = $requestData['order_id'];
        $paymentToken = $requestData['payment_token'];
        $response = $this->payMobService->confirmPayment($paymentToken, $orderID);

        return response()->json($response);
    }
}
