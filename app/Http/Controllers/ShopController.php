<?php

namespace App\Http\Controllers;

use App\Order;
use App\Address;
use App\Product;
use App\Category;
use App\Inventory;
use Stripe\Stripe;
use App\PaymentSource;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Helpers\SparkPostHelper;
use Stripe\Charge as StripeCharge;
use Stripe\Customer as StripeCustomer;

class ShopController extends Controller {

    /**
     * Shop - Home
     * URL: /shop
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $order = !empty($request->get('order')) ? $request->get('order') : 'asc';

        $perPage = 12;

        // Retrieve all Products
        $products = Product::select('products.*');

        switch ($request->get('sort')) {
            case 'date':
                $products = $products->orderBy('created_at', $order);
                break;

            case 'price':
                $products = $products->orderBy('price', $order);
                break;

            case 'name':
                $products = $products->orderBy('name', $order);
                break;

            default:
                $products = $products->orderBy('order');
                break;
        }

        $products = $products->paginate($perPage);

        return view('shop.index', compact('products'));
    }

    /**
     * Shop - Category
     * URL: /shop/c/{uri}
     *
     * @param Request $request
     * @param $uri
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category(Request $request, $uri)
    {
        $order = !empty($request->get('order')) ? $request->get('order') : 'asc';

        $perPage = 12;

        // Retrieve the current Category
        $category = Category::where('uri', $uri)->first();

        // Retrieve Products in this Category
        $products = Product::select('products.*')->where('category_id', $category['id']);

        switch ($request->get('sort')) {
            case 'date':
                $products = $products->orderBy('created_at', $order);
                break;

            case 'price':
                $products = $products->orderBy('price', $order);
                break;

            case 'name':
                $products = $products->orderBy('name', $order);
                break;

            default:
                $products = $products->orderBy('order');
                break;
        }

        $products = $products->paginate($perPage);

        return view('shop.categories.show', compact('category', 'products'));
    }

    /**
     * Shop - Checkout
     * URL: /shop/checkout
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function checkout() {
        // Cart
        $cart = session('cart');

        if (!$cart || (!empty($cart['items']) && count($cart['items']) == 0)) {
            return redirect(route('shop'))->with('alert-warning', 'The shopping cart is currently empty. Please add items to cart first.');
        }

        $fees = CustomHelper::computeFees($cart);

        $shipping = [
            'config' => config('custom.checkout.shipping')
        ];

        $shipping['default'] = $shipping['config']['default'];

        return view('shop.checkout.index', compact('cart', 'fees', 'shipping'));
    }

    /**
     * Shop - Submit Checkout
     * URL: /shop/checkout (POST)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkoutSubmit(Request $request) {
        $data = $request->all();

        $orderData = [];

        // Retrieve cart info from session
        $cart = session('cart');

        // Whether Pay Now or Pay Later
        $payNow = !(config('custom.checkout.pay_later') && isset($data['pay_later']));

        // If customer is logged in
        if (auth()->check()) {
            // Validation
            if ($payNow) {
                $this->validate($request, [
                    'delivery.name'      => 'sometimes|required|max:255',
                    'delivery.address_1' => 'sometimes|required|max:255',
                    'delivery.address_2' => 'sometimes|max:255',
                    'delivery.city'      => 'sometimes|required|max:255',
                    'delivery.state'     => 'sometimes|required|size:2',
                    'delivery.zipcode'   => 'sometimes|required|max:20',
                    'delivery.phone'     => 'sometimes|required|max:20',
                    'billing.name'       => 'sometimes|required|max:255',
                    'billing.address_1'  => 'sometimes|required|max:255',
                    'billing.address_2'  => 'sometimes|max:255',
                    'billing.city'       => 'sometimes|required|max:255',
                    'billing.state'      => 'sometimes|required|size:2',
                    'billing.zipcode'    => 'sometimes|required|max:20',
                    'billing.phone'      => 'sometimes|required|max:20',
                    'notes'              => 'sometimes|max:255',
                    'stripe_token'       => 'required_without:card_id', // required stripe_token if card_id is NOT present
                ]);
            } else {
                $this->validate($request, [
                    'delivery.name'      => 'sometimes|required|max:255',
                    'delivery.address_1' => 'sometimes|required|max:255',
                    'delivery.address_2' => 'sometimes|max:255',
                    'delivery.city'      => 'sometimes|required|max:255',
                    'delivery.state'     => 'sometimes|required|size:2',
                    'delivery.zipcode'   => 'sometimes|required|max:20',
                    'delivery.phone'     => 'sometimes|required|max:20',
                    'notes'              => 'sometimes|max:255',
                ]);
            }

            $user = auth()->user();

            $orderData['user_id'] = $user['id'];

            // Contact
            $orderData['contact_email'] = $user['email'];
        }
        // If customer is a guest
        else {
            // Validation
            if ($payNow) {
                $this->validate($request, [
                    'contact_email'      => 'required|max:255|email',
                    'delivery.name'      => 'required|max:255',
                    'delivery.address_1' => 'required|max:255',
                    'delivery.address_2' => 'sometimes|max:255',
                    'delivery.city'      => 'required|max:255',
                    'delivery.state'     => 'required|size:2',
                    'delivery.zipcode'   => 'required|max:20',
                    'delivery.phone'     => 'required|max:20',
                    'billing.name'       => 'sometimes|required|max:255',
                    'billing.address_1'  => 'sometimes|required|max:255',
                    'billing.address_2'  => 'sometimes|max:255',
                    'billing.city'       => 'sometimes|required|max:255',
                    'billing.state'      => 'sometimes|required|size:2',
                    'billing.zipcode'    => 'sometimes|required|max:20',
                    'billing.phone'      => 'sometimes|required|max:10',
                    'notes'              => 'sometimes|max:255',
                    'stripe_token'       => 'required',
                ]);
            } else {
                $this->validate($request, [
                    'contact_email'      => 'required|max:255|email',
                    'delivery.name'      => 'required|max:255',
                    'delivery.address_1' => 'required|max:255',
                    'delivery.address_2' => 'sometimes|max:255',
                    'delivery.city'      => 'required|max:255',
                    'delivery.state'     => 'required|size:2',
                    'delivery.zipcode'   => 'required|max:20',
                    'delivery.phone'     => 'required|max:10',
                    'notes'              => 'sometimes|max:255',
                ]);
            }

            // Contact
            $orderData['contact_email'] = $data['contact_email'];
        }

        // Notes
        if (!empty($data['notes'])) {
            $orderData['notes'] = $data['notes'];
        }

        // Generate a random & unique order number for this order
        $orderData['order_number'] = $this->__generateOrderNumber();

        // Generate a random confirmation code for this order
        $orderData['confirmation_code'] = strtoupper(str_random(6));

        // Fees
        $fees = CustomHelper::computeFees($cart);

        $orderData['subtotal'] = $fees['subtotal'];
        $orderData['tax'] = $fees['tax'];
        $orderData['shipping_fee'] = $fees['shipping'];
        $orderData['total'] = $fees['total'];

        // Stripe
        if ($payNow && !empty($data['stripe_token'])) {
            $orderData['token'] = $data['stripe_token'];
        }

        // Shipping
        if (isset($data['cash_on_delivery'])) {
            $orderData['cash_on_delivery'] = true;
        } else {
            foreach ($data['shipping'] as $k => $v) {
                $orderData['shipping_carrier'] = $k;
                $orderData['shipping_plan'] = $v;

                break;
            }
        }

        // Addresses - Delivery
        // If customer is logged in, and selected an existing delivery address (or selected a newly created delivery address from the modal)
        if (!empty($data['delivery']['address_id'])) {
            $deliveryAddress = Address::find($data['delivery']['address_id']);

            // Set up delivery address for the order
            $orderData['delivery_name'] = $deliveryAddress['name'];
            $orderData['delivery_phone'] = $deliveryAddress['phone'];
            $orderData['delivery_address_1'] = $deliveryAddress['address_1'];
            $orderData['delivery_address_2'] = $deliveryAddress['address_2'];
            $orderData['delivery_city'] = $deliveryAddress['city'];
            $orderData['delivery_state'] = $deliveryAddress['state'];
            $orderData['delivery_zipcode'] = $deliveryAddress['zipcode'];
        }
        // Else, if this is a guest, or a customer w/ no saved delivery addresses, a new delivery address is submitted
        else {
            // Set up delivery address for the order
            $orderData['delivery_name'] = $data['delivery']['name'];
            $orderData['delivery_phone'] = $data['delivery']['phone'];
            $orderData['delivery_address_1'] = $data['delivery']['address_1'];
            $orderData['delivery_address_2'] = $data['delivery']['address_2'];
            $orderData['delivery_city'] = $data['delivery']['city'];
            $orderData['delivery_state'] = $data['delivery']['state'];
            $orderData['delivery_zipcode'] = $data['delivery']['zipcode'];

            // If customer is logged in, and has no saved delivery address, add this new one in
            if (auth()->check()) {
                $data['delivery']['is_delivery'] = true;

                // If customer doesn't have a default delivery address yet, set this as default delivery address
                if ($user->deliveryAddress()->count() == 0) {
                    $data['delivery']['default_delivery'] = true;
                }

                $user->addresses()->create($data['delivery']);
            }
        }

        // Addresses - Billing
        // If customer is logged in, and selected an existing billing address (or selected a newly created billing address from the modal)
        if (!empty($data['billing']['address_id'])) {
            $billingAddress = Address::find($data['billing']['address_id']);

            // Set up billing address for the order
            $orderData['billing_name'] = $billingAddress['name'];
            $orderData['billing_phone'] = $billingAddress['phone'];
            $orderData['billing_address_1'] = $billingAddress['address_1'];
            $orderData['billing_address_2'] = $billingAddress['address_2'];
            $orderData['billing_city'] = $billingAddress['city'];
            $orderData['billing_state'] = $billingAddress['state'];
            $orderData['billing_zipcode'] = $billingAddress['zipcode'];
        }
        // Else, if this is a guest, or a customer w/ no saved billing addresses
        else if ($payNow) {
            // If customer would like to use the same selected/input delivery address as billing address
            if (isset($data['copy_address']) && $data['copy_address']) {
                // Set up billing address for the order
                $orderData['billing_name'] = $orderData['delivery_name'];
                $orderData['billing_phone'] = $orderData['delivery_phone'];
                $orderData['billing_address_1'] = $orderData['delivery_address_1'];
                $orderData['billing_address_2'] = $orderData['delivery_address_2'];
                $orderData['billing_city'] = $orderData['delivery_city'];
                $orderData['billing_state'] = $orderData['delivery_state'];
                $orderData['billing_zipcode'] = $orderData['delivery_zipcode'];
            }
            // Else, a new billing address is submitted
            else {
                // Set up billing address for the order
                $orderData['billing_name'] = $data['billing']['name'];
                $orderData['billing_phone'] = $data['billing']['phone'];
                $orderData['billing_address_1'] = $data['billing']['address_1'];
                $orderData['billing_address_2'] = $data['billing']['address_2'];
                $orderData['billing_city'] = $data['billing']['city'];
                $orderData['billing_state'] = $data['billing']['state'];
                $orderData['billing_zipcode'] = $data['billing']['zipcode'];
            }

            // If customer is logged in, and has no saved billing address, add this new one in
            if (auth()->check()) {
                $billingAddress = [
                    'name' => $orderData['billing_name'],
                    'phone' => $orderData['billing_phone'],
                    'address_1' => $orderData['billing_address_1'],
                    'address_2' => $orderData['billing_address_2'],
                    'city' => $orderData['billing_city'],
                    'state' => $orderData['billing_state'],
                    'zipcode' => $orderData['billing_zipcode'],
                    'is_billing' => true
                ];

                // If customer doesn't have a default billing address yet, set this as default billing address
                if ($user->billingAddress()->count() == 0) {
                    $billingAddress['default_billing'] = true;
                }

                $user->addresses()->create($billingAddress);
            }
        }

        // Payment
        if ($payNow) {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // If customer is logged in
            if (auth()->check()) {
                // If customer doesn't have a Stripe customer account yet
                if (empty($user['stripe_customer_id'])) {
                    // Create a Stripe Customer account
                    $stripeCustomer = StripeCustomer::create([
                        'description' => $user['first_name'] . ' ' . $user['last_name'],
                        'email' => $user['email'],
                        'metadata' => [
                            'user_id' => $user['id']
                        ]
                    ]);

                    // Update 'stripe_customer_id' of the local customer
                    $user->stripe_customer_id = $stripeCustomer->id;
                    $user->save();
                } else {
                    // Retrieve customer from Stripe
                    $stripeCustomer =  StripeCustomer::retrieve($user['stripe_customer_id']);
                }

                // If Stripe Card is selected
                if (!empty($data['card_id'])) {
                    $card = PaymentSource::find($data['card_id']);

                    $stripeCard = $stripeCustomer->sources->retrieve($card['vendor_card_id']);

                    // Charge this Stripe customer w/ this Stripe card
                    $charge = StripeCharge::create([
                        'amount' => $fees['total'] * 100, // in cents
                        'currency' => 'usd',
                        'customer' => $stripeCustomer->id,
                        'source' => $stripeCard->id
                    ]);
                }
                // Else, a token must have been submitted
                else if (!empty($data['stripe_token'])) {
                    // If customer wants to save this new payment method
                    if (isset($data['save_payment_method']) && $data['save_payment_method']) {
                        $stripeCard = $stripeCustomer->sources->create(['source' => $data['stripe_token']]);

                        // Charge this Stripe customer w/ this Stripe card
                        $charge = StripeCharge::create([
                            'amount' => $fees['total'] * 100, // in cents
                            'currency' => 'usd',
                            'customer' => $stripeCustomer->id,
                            'source' => $stripeCard->id
                        ]);

                        // Insert this Stripe card to DB
                        PaymentSource::create([
                            'vendor' => 'stripe',
                            'name_on_card' => $stripeCard->name,
                            'last4' => $stripeCard->last4,
                            'brand' => $stripeCard->brand,
                            'type' => 'card',
                            'user_id' => $user['id'],
                            'default' => true, // Since this is the first card
                            'vendor_card_id' => $stripeCard->id
                        ]);
                    }
                    // Else, just charge the given card (by token)
                    else {
                        // Charge w/ this Stripe token
                        $charge = StripeCharge::create([
                            'amount' => $fees['total'] * 100, // in cents
                            'currency' => 'usd',
                            'customer' => $stripeCustomer->id,
                            'source' => $data['stripe_token']
                        ]);
                    }
                }
            }
            // Else, charge w/ the Stripe token
            else {
                $charge = StripeCharge::create([
                    'amount' => $fees['total'] * 100, // in cents
                    'currency' => 'usd',
                    'source' => $data['stripe_token'] // obtained with Stripe.js
                ]);
            }

            $orderData['payment_status'] = 'paid'; // Set Payment Status of this Order to 'paid'
            $orderData['payment_method'] = 'card'; // Set Payment Method of this Order to 'card'
            $orderData['stripe_charge_id'] = $charge->id; // Save Stripe Charge ID to this Order
        } else {
            $orderData['pay_later'] = true;
        }

        $createdOrder = Order::create($orderData);

        // If Order is created
        if ($createdOrder) {
            $orderItems = collect($cart['items'])->reduce(function($carry, $item) {
                // Update inventory
                $inventoryItem = Inventory::find($item['inventory_id']);

                if ($inventoryItem['stock'] > $item['quantity']) {
                    $inventoryItem->decrement('stock', $item['quantity']);
                } else {
                    $inventoryItem->stock = 0;
                    $inventoryItem->save();
                }

                unset($item['product_id'], $item['stock']);

                $carry[$item['inventory_id']] = $item;

                return $carry;
            });

            $createdOrder->inventoryItems()->attach($orderItems);

            // Update Stripe Charge's metadata w/ the Order ID
            if ($payNow) {
                $charge->metadata = ['order_id' => $createdOrder['id']];
                $charge->save();
            }

            session()->forget('cart');

            // Send Order Confirmation email to customer
            $this->__sendEmail('order_confirmation', $createdOrder);

            // Send New Order notification email to Admin
            $this->__sendEmail('new_order', $createdOrder);

            return redirect(route('shop.checkout.confirmation', [$orderData['order_number'], $orderData['confirmation_code']]));
        }

    }

    /**
     * Shop - Checkout - Login
     * URL: /shop/checkout/login (POST)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request) {
        if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return redirect()->intended(route('shop.checkout'));
        } else {
            return back()->with('alert-warning', 'The email and/or password you entered is not correct, please try again.');
        }
    }

    /**
     * Generate a random & unique order number
     *
     * @return int
     */
    private function __generateOrderNumber() {
        $number = mt_rand(1000000000, 9999999999);

        // Re-call this function again if this order number already exists
        if (Order::whereOrderNumber($number)->exists()) {
            return $this->__generateOrderNumber();
        }

        // Otherwise, it's valid and can be used
        return $number;
    }

    /**
     * Send transactional email
     *
     * @param $type
     * @param $data
     */
    private function __sendEmail($type, $data) {
        switch ($type) {
            case 'order_confirmation':
            case 'new_order':
                // If customer is logged in, retrieve name and email from user info
                if (!empty($data['user_id'])) {
                    $data['name'] = auth()->user()->first_name;
                    $data['email'] = auth()->user()->email;
                }
                // Else if customer is a guest, use given billing name for name, and contact email for email
                else {
                    $data['name'] = $data['billing_name'];
                    $data['email'] = $data['contact_email'];
                }

                $sub = [
                    'customer_name' => $data['name'],
                    'customer_email' => $data['email'],
                    'order_number' => (string) $data['order_number'],
                    'confirmation_code' => $data['confirmation_code'],
                    'purchase_date' => $data['created_at']->timezone(config('custom.timezone'))->toDayDateTimeString(),
                    'shipping' => [
                        'carrier' => config('custom.checkout.shipping..carriers.' . $data['shipping_carrier'] . '.name'),
                        'plan' => config('custom.checkout.shipping..carriers.' . $data['shipping_carrier'] . '.plans.' . $data['shipping_plan'] . '.name')
                    ],
                    'billing_address' => [
                        'name'       => $data['billing_name'],
                        'phone'      => $data['billing_phone'],
                        'address_1'  => $data['billing_address_1'],
                        'address_2'  => $data['billing_address_2'],
                        'city'       => $data['billing_city'],
                        'state'      => $data['billing_state'],
                        'zipcode'    => $data['billing_zipcode']
                    ],
                    'delivery_address' => [
                        'name'       => $data['delivery_name'],
                        'phone'      => $data['delivery_phone'],
                        'address_1'  => $data['delivery_address_1'],
                        'address_2'  => $data['delivery_address_2'],
                        'city'       => $data['delivery_city'],
                        'state'      => $data['delivery_state'],
                        'zipcode'    => $data['delivery_zipcode']
                    ],
                    'items' => [],
                    'subtotal' => number_format($data['subtotal'], 2),
                    'tax' => number_format($data['tax'], 2),
                    'shipping_fee' => number_format($data['shipping_fee'], 2),
                    'total' => number_format($data['total'], 2),
                ];

                foreach ($data->inventoryItems as $item) {
                    $tmp = [
                        'name' => $item->product['name'],
                        'url' => route('shop.product', [$item->product['uri'], $item->product['id']]),
                        'unit_price' => number_format($item->pivot['price'], 2),
                        'quantity' => $item->pivot['quantity'],
                        'price' => number_format($item->pivot['price'] * $item->pivot['quantity'], 2)
                    ];

                    if ($item->options()->count() > 0) {
                        $tmp['options'] = [];

                        foreach ($item->options()->get() as $option) {
                            $tmp['options'][] = [
                                'attribute' => $option->attribute['name'],
                                'value' => $option['name']
                            ];
                        }
                    }

                    if ($item->product->defaultPhoto()->count() > 0) {
                        $tmp['image'] = CustomHelper::image($item->product->defaultPhoto['name'], true);
                    }

                    $sub['items'][] = $tmp;
                }

                if ($type == 'order_confirmation') {
                    $templateId = config('custom.emails.templates.order_confirmation');

                    $recipients = [
                        [
                            'address' => $data['email'],
                            'name' => $data['name'],
                            'substitution_data' => $sub
                        ]
                    ];
                }
                else if ($type == 'new_order') {
                    $templateId = config('custom.emails.templates.new_order');

                    $recipients = [
                        [
                            'address' => env('OWNER_EMAIL'),
                            'name' => env('OWNER_NAME'),
                            'substitution_data' => $sub
                        ],
                        [
                            'address' => env('ADMIN_EMAIL'),
                            'name' => env('ADMIN_NAME'),
                            'substitution_data' => $sub
                        ]
                    ];
                }

                SparkPostHelper::sendTemplate($templateId, $recipients);

                break;
        }
    }

}