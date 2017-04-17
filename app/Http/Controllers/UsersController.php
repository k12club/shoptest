<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class UsersController extends Controller {

    /**
     * Update Account
     * URL: /account (POST)
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        $data = $request->all();

        $user = auth()->user();

        // Validation
        $this->validate($request, [
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|max:255|email|unique:users,email,' . $user['id'],
            'password' => 'sometimes|min:6|max:255',
        ]);

        foreach (['first_name', 'last_name', 'email'] as $field) {
            if ($data[$field] != $user->{$field}) {
                $user->{$field} = $data[$field];
            }
        }

        // Set the new password
        if (!empty($data['password'])) {
            $user->{'password'} = bcrypt($data['password']);
        }

        $user->save();

        return redirect(route('account'))->with('alert-success', 'Your profile has been updated successfully.');
    }

    /**
     * Order
     * URL: /orders/{orderNumber}
     *
     * @param $orderNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function order($orderNumber) {
        $order = Order::where('order_number', $orderNumber)->first();

        // Authorization check
        if (auth()->user()->can('view', $order)) {
            return view('users.orders.show', compact('order'));
        } else {
            return redirect(route('orders'))->with('alert-danger', 'You do not have permission to view this order.');
        }
    }
}