<?php

namespace App\Http\Controllers;

use App\Models\MpesaP;
use App\Models\Payment;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function store(){
        $validate = request()->validate([
            'method'=>'required',
            'amount'=>'required',
            'phone'=>'required',
            'uid'=>'required',
            'account'=>'required',
        ]);
        if(!$validate){
            return response()->json(['message'=>'Validation failed'],400);
        }
        $validate['trackingID'] = strtoupper(uniqid());
        Payment::create($validate);
        $this->Pay(request('amount'), request('phone'), $validate['trackingID']);
        return response()->json(['message'=>'Payment created successfully', 'trackingID'=> $validate['trackingID']],201);
        
    }
    public function generateToken()
    {
        $consumer_key = env('MPESA_CONSUMER_KEY');
        $consumer_secret = env('MPESA_CONSUMER_SECRET');
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $res = Http::withBasicAuth($consumer_key, $consumer_secret)
            ->get($url);
        $response = json_decode($res, true);
        return $response['access_token'];
    }
    public function lipaNaMpesaPassword()
    {
        $passkey = env('MPESA_PASSKEY');
        $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp = date('YmdHis');
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode . $passkey . $timestamp);
        return $lipa_na_mpesa_password;
    }
    public function Callback($id)
    {
        $res = request();
        Log::channel('mpesaSuccess')->info(json_encode(['whole' => $res['Body']]));
        // if ($res['Body']['stkCallback']['ResultCode'] == 0) {
        $message = $res['Body']['stkCallback']['ResultDesc'];
        $amount = $res['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
        $TransactionId = $res['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'];
        $phne = $res['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];
        Log::channel('mpesaSuccess')->info(json_encode(['whole' => $res['Body']]));
        MpesaP::create([
            'TransactionType' => 'Paybill',
            'trackingID' => $id,
            'TransAmount' => $amount,
            'MpesaReceiptNumber' => $TransactionId,
            'TransactionDate' => date('d-m-Y'),
            'PhoneNumber' => '+' . $phne,
            'response' => $message
        ]);
        // if ($amount >= 2) {
        $order = Payment::where('trackingID', $id)->first();
        $order->payment_status = 'paid';
        $order->save();
        Log::channel('mpesaSuccess')->info('Account payment successful for order ID: ' . $id);
        $response = new Response();
        $response->headers->set("Content-Type", "text/xml; charset=utf-8");
        $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Success"]));
        return $response;
    }
    function Pay($amount, $contact, $id)
    {
        $url = (env('MPESA_ENV') == 'live') ? 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest' : 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $data = [
            'BusinessShortCode' => env('MPESA_SHORT_CODE'),
            'Password' => $this->lipaNaMpesaPassword(),
            'Timestamp' => date('YmdHis'),
            'TransactionType' => 'CustomerPayBillOnline',
            // 'Amount' => $amount,
            'Amount' => 1,
            'PartyA' => $contact,
            'PartyB' => env('MPESA_SHORT_CODE'),
            'PhoneNumber' => $contact,
            'CallBackURL' => 'https://apektechinc.com/api/payment2/v1/callback/' . $id,
            'AccountReference' => 'Order payment',
            'TransactionDesc' => 'Order payment',
        ];
        $response = Http::withToken($this->generateToken())
            ->post($url, $data);
        $res = $response->json();
        return $res;
    }
    public function verify($code_id)
    {
        $payment = Payment::where('trackingID', $code_id)->first();
        if (!$payment) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json(['payment_status' => $payment->status], 200);
    }
    public function repay($code){
        $payment = Payment::where('trackingID', $code)->first();
        if (!$payment) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        if($payment->status == 'paid'){
            return response()->json(['message' => 'Order already paid'], 404);
        }
        $phone = request('phone')?? $payment->phone;
        $res = $this->Pay($payment->amount, $phone, $payment->trackingID);
        return response()->json(['message' => 'Payment initiated', 'response' => $res], 200);
    }
}
