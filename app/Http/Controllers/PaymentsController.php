<?php

namespace App\Http\Controllers;

use App\PaymentSource;
use Illuminate\Http\Request;

class PaymentsController extends Controller {

    /**
     * Store Payment source
     * URL: /shop/payments/create (POST)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {
        $data = $request->all();

        // If customer is logged in
        if (auth()->check()) {
            $user = auth()->user();

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            // If customer doesn't have a Stripe customer account yet
            if (empty($user['stripe_customer_id'])) {
                // Create a Stripe Customer account
                $stripeCustomer = \Stripe\Customer::create([
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
                $stripeCustomer =  \Stripe\Customer::retrieve($user['stripe_customer_id']);
            }

            // If a token has been submitted, save this Card to this Stripe Customer
            if (!empty($data['stripe_token'])) {
                $stripeCard = $stripeCustomer->sources->create(['source' => $data['stripe_token']]);
            }

            $card = [
                'vendor' => 'stripe',
                'name_on_card' => $stripeCard->name,
                'last4' => $stripeCard->last4,
                'brand' => $stripeCard->brand,
                'type' => 'card',
                'vendor_card_id' => $stripeCard->id
            ];

            // If User has no primary card yet, set this new card as default (primary)
            if ($user->primaryCard()->count() == 0) {
                $card['default'] = true;
            }

            $createdCard = PaymentSource::create($card);

            if ($createdCard) {
                $createdCard->user()->associate($user);

                $createdCard->save();

                if ($request->ajax()) {
                    return response()->json([
                        'status' => true,
                        'card_id' => $createdCard['id'],
                        'name' => $stripeCard->name,
                        'last4' => $stripeCard->last4,
                        'brand' => $stripeCard->brand
                    ]);
                }
            }
        }
    }

    /**
     * Update Payment source
     * /payments/{paymentSource} (POST)
     *
     * @param Request $request
     * @param $paymentSource
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $paymentSource) {
        // Authorization check
        if (!auth()->user()->can('view', $paymentSource)) {
            return redirect(route('payments'))->with('alert-danger', 'You do not have permission to update this payment source.');
        }

        $data = $request->all();

        // Sanitizing
        $data['default'] = isset($data['default']);

        // If this card is going to be primary, then set other cards owned by this user non-primary
        if ($data['default']) {
            PaymentSource::where('user_id', auth()->user()->id)->where('id', '!=', $paymentSource['id'])->update(['default' => false]);
        }

        foreach ([
                     'default',
                 ] as $field) {
            if (isset($data[$field]) && $data[$field] != $paymentSource->{$field}) {
                $paymentSource->{$field} = $data[$field];
            }
        }

        $paymentSource->save();

        return redirect(route('payments'))->with('alert-success', 'Your payment method has been updated successfully.');
    }

    /**
     * Remove Payment source
     * /payments/{paymentSource} (DELETE)
     *
     * @param Request $request
     * @param $paymentSource
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $paymentSource) {
        // Authorization check
        if (!auth()->user()->can('view', $paymentSource)) {
            if ($request->ajax()){
                return response()->json(['status' => false, 'message' => 'You do not have permission to remove this payment source.']);
            }

            return redirect(route('payments'))->with('alert-danger', 'You do not have permission to remove this payment source.');
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Retrieve customer from Stripe
        $stripeCustomer =  \Stripe\Customer::retrieve($paymentSource->user['stripe_customer_id']);

        // Retrieve the Stripe card from the given Payment source
        $stripeCard = $stripeCustomer->sources->retrieve($paymentSource['vendor_card_id']);

        // Remove this Stripe card form this Stripe customer
        $stripeCard->delete();

        // Remove this local Payment source
        $paymentSource->delete();

        return redirect(route('payments'))->with('alert-success', 'Your payment method has been removed successfully.');
    }
}