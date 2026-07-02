<?php

namespace App\Http\Controllers;

use App\Models\Mpesa;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log ;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'payphone' => 'required|string|max:20',
            'items' => 'required|string',
            'total' => 'required|numeric',
        ]);
        if (!$validated) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validated], 422);
        }
        // Generate a unique order code if not provided
        if (empty($validated['order_code'])) {
            $validated['order_code'] = strtoupper(uniqid('ORDER_'));
        }

        $order = Order::create($validated);
        $this->Pay($validated['total'], $validated['payphone'], $validated['order_code']);
        return response()->json(['message' => 'Order created successfully', 'order_code' => $order->order_code], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($code_id)
    {
        $order = Order::where('order_code', $code_id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json(['order' => $order], 200);
        //
    }
    public function check_payment_status($code_id)
    {
        $order = Order::where('order_code', $code_id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json(['payment_status' => $order->payment_status], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
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
        Mpesa::create([
            'TransactionType' => 'Paybill',
            'order_code' => $id,
            'TransAmount' => $amount,
            'MpesaReceiptNumber' => $TransactionId,
            'TransactionDate' => date('d-m-Y'),
            'PhoneNumber' => '+' . $phne,
            'response' => $message
        ]);
        // if ($amount >= 2) {
        $order = Order::where('order_code', $id)->first();
        $order->payment_status = 'paid';
        $order->save();
        Log::channel('mpesaSuccess')->info('Order payment successful for order ID: ' . $id);
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
            'Amount' => $amount,
            'PartyA' => $contact,
            'PartyB' => env('MPESA_SHORT_CODE'),
            'PhoneNumber' => $contact,
            'CallBackURL' => 'https://apektechinc.com/api/payment/v1/callback/' . $id,
            'AccountReference' => 'Order payment',
            'TransactionDesc' => 'Order payment',
        ];
        $response = Http::withToken($this->generateToken())
            ->post($url, $data);
        $res = $response->json();
        return $res;
    }
}
