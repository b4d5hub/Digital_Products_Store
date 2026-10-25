<?php

namespace App\Http\Controllers;

use App\Mail\OrderCompleted;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function paypal(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->whereKey($validated['product_id'])
            ->where('is_visible', true)
            ->firstOrFail();

        if ($validated['quantity'] > $product->quantity) {
            throw ValidationException::withMessages([
                'quantity' => 'The requested quantity is no longer available.',
            ]);
        }

        $quantity = (int) $validated['quantity'];
        $unitPrice = (float) $product->price;
        $totalPrice = number_format($unitPrice * $quantity, 2, '.', '');

        session()->put('url.intended', URL::previous());

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('success'),
                "cancel_url" => route('cancel')
            ],
            "purchase_units" => [
                [
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "category" => "DIGITAL_GOODS",
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $totalPrice,
                    ],
                    "shipping_preference" => "NO_SHIPPING",
                ],
            ]
        ]);

        if (isset($response['id'])) {
            foreach ($response['links'] as $link) {
                if($link['rel'] === 'approve') {
                    session()->put('product_id', $product->id);
                    session()->put('quantity', $quantity);
                    session()->put('email', $validated['email']);

                    $buyerIp = $request->header('X-Forwarded-For') ? explode(',', $request->header('X-Forwarded-For'))[0] : $request->ip();
                    $buyerUserAgent = $request->header('User-Agent');

                    $customer = Customer::firstOrCreate(
                        ['email' => $validated['email']],
                        ['ip' => $buyerIp, 'useragent' => $buyerUserAgent]
                    );
                    
                    $order = new Order;
                    $order->customer_id = $customer->id;
                    $order->product_id = $product->id;
                    $order->order_number = (string) Str::uuid();
                    $order->status = 'Pending';
                    $order->quantity = $quantity;
                    $order->unit_price = $unitPrice;
                    $order->notes = 'Payment made via PayPal';
                    $order->download_data = $product->digital_item;
                    $order->download_token = null;
                    $order->save();
                    
                    session()->put('order_id', $order->id);

                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()
            ->route('checkout', ['slug' => $product->slug])
            ->with('message', 'PayPal could not start this checkout. Please try again.');
    }




    public function success(Request $request)
    {
        $request->validate(['token' => ['required', 'string']]);

        $order = Order::find(session('order_id'));

        if (! $order) {
            return redirect()->route('home')->with('message', 'Your checkout session has expired.');
        }

        if ($order->status === 'Completed') {
            return $this->deliveryRedirect($order);
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);

        $capturedTotal = data_get($response, 'purchase_units.0.payments.captures.0.amount.value');
        $expectedTotal = number_format($order->unit_price * $order->quantity, 2, '.', '');

        if (($response['status'] ?? null) !== 'COMPLETED' || ! $capturedTotal || ! hash_equals($expectedTotal, number_format((float) $capturedTotal, 2, '.', ''))) {
            $order->status = 'Declined';
            $order->save();

            return redirect()
                ->route('checkout', ['slug' => $order->product->slug])
                ->with('message', 'Payment could not be verified. Please try again.');
        }

        $order->status = 'Completed';
        $order->download_token = Str::random(64);
        $order->save();

        try {
            Mail::to(session('email'))->send(new OrderCompleted($order));
        } catch (\Throwable $exception) {
            report($exception);
        }

        return $this->deliveryRedirect($order);
    }
    
    public function cancel()
    {
        $order = Order::find(session('order_id'));

        if (! $order) {
            return redirect()->route('home')->with('message', 'Payment was cancelled.');
        }

        if ($order->status === 'Pending') {
            $order->status = 'Declined';
            $order->save();
        }

        return redirect()
            ->route('checkout', ['slug' => $order->product->slug])
            ->with('message', 'Payment was cancelled.');
    }

    public function deliver(Request $request, $order_number, $orderTimestamp, $download_token)
    {
        $order = Order::where('download_token', $download_token)
        ->where('order_number', $order_number)
        ->where('status', 'Completed')
        ->firstOrFail();
        
        return view('success', [
            'order' => $order,
            'order_number' => $order->order_number,
            'test' => $orderTimestamp
        ]);
    }

    private function deliveryRedirect(Order $order)
    {
        return redirect()->route('successtest', [
            'order_number' => $order->order_number,
            'orderTimestamp' => strtotime($order->created_at),
            'download_token' => $order->download_token,
        ]);
    }


}
