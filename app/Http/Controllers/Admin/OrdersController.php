<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use Carbon\Carbon;
use Stripe\Stripe;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Helpers\SparkPostHelper;
use Stripe\Refund as StripeRefund;
use App\Http\Controllers\Controller;
use Stripe\Error\InvalidRequest as StripeInvalidRequest;

class OrdersController extends Controller {

    /**
     * Admin - Orders
     * URL: /admin/orders
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();

        // Stats
        $stats = [];

        // Stats - Orders - Processing
        $stats['orders_processing'] = Order::where('status', 'processing')->count();

        // Stats - Orders - Processing - Total
        $stats['orders_processing_total'] = Order::where('status', 'processing')->sum('total');

        // Stats - Orders - Month to Date
        $stats['orders_month_to_date'] = Order::where('created_at', '>=', Carbon::now('America/Los_Angeles')->startOfMonth())->count();
        //$stats['orders_month_to_date'] = Order::where('created_at', '>=', $now->modify('first day of this month'))->count(); //this works

        // Stats - Orders - Month to Date - Total
        $stats['orders_month_to_date_total'] = Order::where('created_at', '>=', Carbon::now('America/Los_Angeles')->startOfMonth())->sum('total');

        // Stats - Orders - Lifetime - Count
        $stats['orders_lifetime'] = Order::count();

        // Stats - Orders - Lifetime - Total
        $stats['orders_lifetime_total'] = Order::sum('total');

        return view('admin.orders.index', [
            'orders' => $orders,
            'stats'  => $stats
        ]);
    }

    /**
     * Admin - Edit Order
     * URL: /admin/orders/{order}/update (POST)
     *
     * @param Request $request
     * @param $order
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $order)
    {
        $data = $request->all();

        // Refund requested
        if ($request->has('refund')) {
            if (!empty($order['stripe_charge_id'])) {
                Stripe::setApiKey(env('STRIPE_SECRET'));

                try {
                    $refund = StripeRefund::create(array(
                        'charge' => $order['stripe_charge_id']
                    ));
                } catch (StripeInvalidRequest $exception) {
                    return back()->with('alert-danger', $exception->getMessage());
                }

                if (!empty($refund['status']) && $refund['status'] == 'succeeded' && !empty($refund['id'])) {
                    $order->status = 'refunded';
                    $order->payment_status = 'refunded';
                    $order->stripe_refund_id = $refund['id'];
                    $order->save();

                    return back()->with('alert-success', 'You have successfully refunded this order.');
                } else {
                    return back()->with('alert-danger', 'The refund was not processed successfully, please try again or contact the administrator.');
                }
            }
        }

        // Validation
        $this->validate($request, [
            'order_number'       => 'required|digits:10',
            'confirmation_code'  => 'required|alpha_num|size:6',
            'contact_email'      => 'required|max:255|email',
            'delivery_name'      => 'required|max:255',
            'delivery_address_1' => 'required|max:255',
            'delivery_address_2' => 'sometimes|max:255',
            'delivery_city'      => 'required|max:255',
            'delivery_state'     => 'required|size:2',
            'delivery_zipcode'   => 'required|max:20',
            'delivery_phone'     => 'required|max:20',
            'billing_name'       => 'required_if:payment_method,card|max:255',
            'billing_address_1'  => 'required_if:payment_method,card|max:255',
            'billing_address_2'  => 'max:255',
            'billing_city'       => 'required_if:payment_method,card|max:255',
            'billing_state'      => 'required_if:payment_method,card|size:2',
            'billing_zipcode'    => 'required_if:payment_method,card|max:20',
            'billing_phone'      => 'required_if:payment_method,card|max:20',
            'payment_status'     => 'in:not_paid,paid,refunded',
            'payment_method'     => 'in:cash,card',
        ]);

        foreach ([
            'order_number',
            'confirmation_code',
            'contact_email',
            'shipping_carrier',
            'shipping_plan',
            'shipping_tracking_number',
            'delivery_name',
            'delivery_address_1',
            'delivery_address_2',
            'delivery_city',
            'delivery_state',
            'delivery_zipcode',
            'delivery_phone',
            'billing_name',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_state',
            'billing_zipcode',
            'billing_phone',
            'status',
            'payment_status',
            'payment_method',
        ] as $field) {
            if ($data[$field] != $order->{$field}) {
                $order->{$field} = $data[$field];
            }
        }

        $order->save();

        return back()->with('alert-success', 'The order has been updated successfully.');
    }

    /**
     * Send email to customer regarding the order (i.e. shipping confirmation)
     *
     * @param $order
     * @param $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function email($order, $type) {

        switch ($type) {
            case 'shipping_confirmation':
                if ($order['status'] == 'shipped' && !empty($order['shipping_tracking_number'])) {
                    // Send Shipping Confirmation email to customer
                    $templateId = config('custom.emails.templates.shipping_confirmation');

                    if (!empty($order['user_id'])) {
                        $user = $order->user;
                    }

                    $sub = [
                        'customer_name' => !empty($order['user_id']) ? $user['first_name'] : $order['billing_name'],
                        'order_number' => $order['order_number'],
                        'shipping_carrier' => !empty($order['shipping_carrier']) ? config('custom.checkout.shipping.carriers.' . $order['shipping_carrier'] . '.name') : '',
                        'shipping_plan' => !empty($order['shipping_plan']) ? config('custom.checkout.shipping.carriers.' . $order['shipping_carrier'] . '.plans.' . $order['shipping_plan'] . '.plan') : '',
                        'tracking_url' => 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . $order['shipping_tracking_number'],
                        'tracking_number' => $order['shipping_tracking_number'],
                        'delivery_address' => [
                            'name'       => $order['delivery_name'],
                            'phone'      => $order['delivery_phone'],
                            'address_1'  => $order['delivery_address_1'],
                            'address_2'  => $order['delivery_address_2'],
                            'city'       => $order['delivery_city'],
                            'state'      => $order['delivery_state'],
                            'zipcode'    => $order['delivery_zipcode']
                        ]
                    ];

                    foreach ($order->inventoryItems as $item) {
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

                    $recipients = [
                        [
                            'address' => !empty($order['contact_email']) ? $order['contact_email'] : $user['email'],
                            'name' => $sub['customer_name'],
                            'substitution_data' => $sub
                        ]
                    ];

                    SparkPostHelper::sendTemplate($templateId, $recipients);

                    return back()->with('alert-success', 'You have successfully sent a Shipping Confirmation email to the customer.');
                } else {
                    return back()->with('alert-danger', 'You need to change the status to Shipped and add a Tracking Number first in order to send this Shipping Confirmation email to the customer.');
                }

                break;
        }
    }

}