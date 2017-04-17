<?php

namespace App\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;

class AddressesController extends Controller {

    /**
     * Create/edit address
     * URL: /shop/addresses/manage (POST)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function manage(Request $request) {
        $data = $request->all();

        // Pre-sanitizing
        if (isset($data['is_delivery']) && ($data['is_delivery'] == 'true' || $data['is_delivery'] === true)) {
            $data['is_delivery'] = true;
        }

        if (isset($data['is_billing']) && ($data['is_billing'] == 'true' || $data['is_billing'] === true)) {
            $data['is_billing'] = true;
        }

        // Validation
        $this->validate($request, [
            'name' => 'required|max:255',
            'phone' => 'min:10|max:255',
            'address_1' => 'required|max:255',
            'address_2' => 'max:255',
            'city' => 'required|max:255',
            'state' => 'required|size:2',
            'zipcode' => 'required|max:20',
        ]);

        // Sanitizing
        $data['phone'] = preg_replace("/[^0-9]/","", $data['phone']); // Just keep numbers

        $id = $data['id'];

        if (empty($id)) {
            $address = Address::create($data);

            if ($address) {
                $address->user()->associate(auth()->user());

                $address->save();
            }

            if ($request->ajax()) {
                return response()->json(['status' => true, 'address_id' => $address['id'], 'act' => 'create']);
            }
        } else {
            $address = Address::find($id);

            foreach ([
                         'name',
                         'phone',
                         'address_1',
                         'address_2',
                         'city',
                         'state',
                         'zipcode'
                     ] as $field) {
                if (isset($data[$field]) && $data[$field] != $address->{$field}) {
                    $address->{$field} = $data[$field];
                }
            }

            $address->save();

            if ($request->ajax()) {
                return response()->json(['status' => true, 'address_id' => $address['id'], 'act' => 'edit']);
            }
        }
    }

    /**
     * Store Address
     * URL: /addresses (POST)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'type' => 'required|in:delivery,billing',
            'name' => 'required|max:255',
            'phone' => 'min:10|max:255',
            'address_1' => 'required|max:255',
            'address_2' => 'max:255',
            'city' => 'required|max:255',
            'state' => 'required|size:2',
            'zipcode' => 'required|max:20',
        ]);

        // Sanitizing
        $data['phone'] = preg_replace("/[^0-9]/","", $data['phone']); // Just keep numbers

        if ($data['type'] == 'delivery') {
            $data['is_delivery'] = true;

            if (auth()->user()->deliveryAddresses()->count() == 0) {
                $data['default_delivery'] = true;
            }
        }

        if ($data['type'] == 'billing') {
            $data['is_billing'] = true;

            if (auth()->user()->billingAddresses()->count() == 0) {
                $data['default_billing'] = true;
            }
        }

        $createdAddress = Address::create($data);

        if ($createdAddress) {
            $createdAddress->user()->associate(auth()->user());
            $createdAddress->save();

            return redirect(route('addresses'))->with('alert-success', 'The address has been added successfully.');
        } else {
            return back()->with('alert-danger', 'The address cannot be saved, please try again or contact the administrator.');
        }
    }

    /**
     * Update Address
     * URL: /addresses/{address} (POST)
     *
     * @param Request $request
     * @param $address
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $address) {
        // Authorization check
        if (!auth()->user()->can('view', $address)) {
            return redirect(route('addresses'))->with('alert-danger', 'You do not have permission to update this address.');
        }

        $data = $request->all();

        // Pre-sanitizing
        if (isset($data['is_delivery']) && ($data['is_delivery'] == 'true' || $data['is_delivery'] === true)) {
            $data['is_delivery'] = true;
        }

        if (isset($data['is_billing']) && ($data['is_billing'] == 'true' || $data['is_billing'] === true)) {
            $data['is_billing'] = true;
        }

        // Validation
        $this->validate($request, [
            'name' => 'required|max:255',
            'phone' => 'min:10|max:255',
            'address_1' => 'required|max:255',
            'address_2' => 'max:255',
            'city' => 'required|max:255',
            'state' => 'required|size:2',
            'zipcode' => 'required|max:20',
        ]);

        // Sanitizing
        $data['phone'] = preg_replace("/[^0-9]/","", $data['phone']); // Just keep numbers

        foreach ([
                     'name',
                     'phone',
                     'address_1',
                     'address_2',
                     'city',
                     'state',
                     'zipcode'
                 ] as $field) {
            if (isset($data[$field]) && $data[$field] != $address->{$field}) {
                $address->{$field} = $data[$field];
            }
        }

        $address->save();

        return redirect(route('addresses.edit', $address['id']))->with('alert-success', 'Your address has been updated successfully.');
    }

    /**
     * Set Address primary
     * URL: /addresses/{address}/primary
     *
     * @param Request $request
     * @param $address
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function primary(Request $request, $address) {
        // If this is a delivery address
        if ($address['is_delivery']) {
            // Set other delivery addresses non-primary
            Address::where([
                'user_id' => auth()->user()->id,
                'is_delivery' => true,
            ])->where(
                'id', '!=', $address['id']
            )->update([
                'default_delivery' => false
            ]);

            // Set this as primary delivery address
            $address->default_delivery = true;
        }
        // If this is a billing address
        else if ($address['is_billing']) {
            // Set other billing addresses non-primary
            Address::where([
                'user_id' => auth()->user()->id,
                'is_billing' => true,
            ])->where(
                'id', '!=', $address['id']
            )->update([
                'default_billing' => false
            ]);

            // Set this as primary billing address
            $address->default_billing = true;
        }

        $address->save();

        if($request->ajax()){
            return response()->json(['status' => true]);
        }

        return redirect(route('addresses'))->with('alert-success', 'The address has been set to primary successfully.');
    }

    /**
     * Remove Address
     * URL: /addresses/{address} (DELETE)
     *
     * @param Request $request
     * @param $address
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $address) {
        // Authorization check
        if (!auth()->user()->can('view', $address)) {
            if ($request->ajax()){
                return response()->json(['status' => false, 'message' => 'You do not have permission to remove this address.']);
            }

            return redirect(route('addresses'))->with('alert-danger', 'You do not have permission to remove this address.');
        }

        if ($address['default_delivery'] || $address['default_billing']) {
            if ($request->ajax()){
                return response()->json(['status' => false, 'error' => ['msg' => 'You cannot delete the primary address.']]);
            }

            return back()->with('alert-danger', 'You cannot delete the primary address.');
        }

        $address->delete();

        if ($request->ajax()){
            return response()->json(['status' => true]);
        }

        return redirect(route('addresses'))->with('alert-success', 'The address has been removed successfully.');
    }
}