<?php

namespace App\Http\Controllers;

use App\Mail\OrderCompleted;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function paypal(Request $request)
    {

        // Store the previous URL before redirecting to PayPal
        session()->put('url.intended', URL::previous());
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('success'),
                "cancel_url" => route('cancel')
            ],
            "purchase_units" => [
                [
                    "name" => $request->product_name,
                    "quantity" => $request->quantity,
                    "category" => "DIGITAL_GOODS", // OR 'DONATION' OR 'PHYSICAL_GOODS'
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->price * $request->quantity,
                    ],
                    "shipping_preference" => "NO_SHIPPING", // Set the shipping preference here
                ],
            ]
        ]);
        // dd($response);
        if(isset($response['id']) && $response['id']!=null) {
            foreach($response['links'] as $link) {
                if($link['rel'] === 'approve') {
                    // Store necessary data in the session
                    session()->put('product_name', $request->product_name);
                    session()->put('product_id', $request->product_id);
                    session()->put('quantity', $request->quantity);
                    session()->put('price', $request->price);
                    session()->put('email', $request->email);


                    $quantity = session()->get('quantity');
                    $productId = session()->get('product_id');
                    $price = session()->get('price');
                    $downloaddata = Product::where('id', $productId)->value('digital_item');
                    
                    // Get buyer's IP address and user agent 
                    $customerEmail = session()->get('email');
                    $buyerIp = $request->header('X-Forwarded-For') ? explode(',', $request->header('X-Forwarded-For'))[0] : $request->ip();
                    $buyerUserAgent = $request->header('User-Agent');

                    // Find or create the customer
                    $customer = Customer::firstOrCreate(
                        ['email' => $customerEmail], // Search criteria
                        ['ip' => $buyerIp, 'useragent' => $buyerUserAgent] // Attributes to fill on creation
                    );
                    
                    // Create new order Pending
                    $order = new Order;
                    $order->customer_id = $customer->id;
                    $order->product_id = $productId; // Assuming a static product id or fetch from session if dynamic
                    $order->order_number = uniqid('order_'); // or any other logic to generate order number
                    $order->status = 'Pending';
                    $order->quantity = $quantity;
                    $order->unit_price = $price; // Calculating unit price
                    // Zid paypal_transaction_id f table orders ozid lwebhook 3la 7sab disputes fppl
                    // $order->paypal_transaction_id = $response['purchase_units'][0]['payments']['captures'][0]['id'];
                    $order->notes = 'Payment made via PayPal';
                    $order->download_data = $downloaddata;
                    $order->download_token = null;
                    $order->save();
                    
                    session()->put('order_id', $order->id); // Store order ID for later use

                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('cancel');
        }
    }




    public function success(Request $request)
    {
    $provider = new PayPalClient;
    $provider->setApiCredentials(config('paypal'));
    $paypalToken = $provider->getAccessToken();
    $response = $provider->capturePaymentOrder($request->token);
    
    if(isset($response['status']) && $response['status'] == 'COMPLETED') {
    
    // Fetch session data
    // $productName = session()->get('product_name');
    // $customerEmail = $request->email; // assuming the email is posted from the form
     
    $customerEmail = session()->get('email');
    $quantity = session()->get('quantity');
    $totalPrice = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];


    // Edit order status
    $orderId = session()->get('order_id');
    $order = Order::find($orderId);
    $order->status = 'Completed';
    $order->unit_price = $totalPrice / $quantity; // Calculating unit price
    $order->download_token = md5($order->product_id . $order->customer_id);
    $order->save();

    // Send payment completed email
    Mail::to($customerEmail)->send(new OrderCompleted($order));

    
    // // Create order items
    // $orderItem = new OrderItem;
    // $orderItem->order_id = $order->id;
    // $orderItem->product_id = $productId; // Assuming a static product id or fetch from session if dynamic
    // $orderItem->quantity = $quantity;
    // $orderItem->unit_price = $totalPrice / $quantity; // Calculating unit price
    // $orderItem->save();
    

    // Clear session data
    session()->forget('product_id');
    session()->forget('product_name');
    session()->forget('quantity');
    
    // // Pass product description to success route
    $routeParameters = [
        'order_number' => $order->order_number,
        'orderTimestamp' => strtotime($order->created_at),
        'download_token' => $order->download_token
    ];
    
    // session()->flash('successmessage', 'Payment is COMPLETED - Check your e-mail bla bla.');
    // return redirect()->route('successtest', $routeParameters);


    // return "Payment is COMPLETED";
    return redirect()->route('successtest', $routeParameters);
    } else {
    return redirect()->route('cancel');
    }
    }
    
    public function cancel()
    {
        // Flash a message to the session
        session()->flash('message', 'Payment is CANCELLED.');
        $orderId = session()->get('order_id');
        $order = Order::find($orderId);
        $order->status = 'Declined';
        $order->save();
    

        // Redirect back to the previous page
        return redirect()->intended();
    }

    public function deliver(Request $request, $order_number, $orderTimestamp, $download_token)
    {
        $order = Order::where('download_token', $download_token)
        ->where('order_number', $order_number)
        // ->where('created_at', date($orderTimestamp))
        ->firstOrFail();
        
        return view('success', [
            'order' => $order,
            'order_number' => $order->order_number,
            'test' => $orderTimestamp
        ]);
    }


}