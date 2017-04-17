<?php

namespace App\Http\Controllers\Admin;

use App\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OptionsController extends Controller {

    /**
     * Admin - Store Option
     * /admin/attributes/{attribute}/options (POST)
     *
     * @param Request $request
     * @param $attribute
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function store(Request $request, $attribute)
    {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name'  => 'required|max:45|unique:options',
            'order' => 'required|integer|min:1'
        ]);

        $createdOption = Option::create($data);

        if ($createdOption) {
            // Associate this Option with the parent Attribute
            $createdOption->attribute()->associate($attribute)->save();

            return redirect(route('admin.attributes.attribute', $attribute['id']))->with('alert-success', 'The option has been added successfully.');
        } else {
            return back()->with('alert-danger', 'The option was not saved successfully, please try again or contact the administrator.');
        }
    }

    /**
     * Admin - Update Option
     * /admin/attributes/{attribute}/options/{option} (POST)
     *
     * @param Request $request
     * @param $attribute
     * @param $option
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function update(Request $request, $attribute, $option)
    {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name'  => 'required|max:45|unique:options,name,' .  $option['id'],
            'order' => 'required|integer|min:1'
        ]);

        foreach (['name', 'order'] as $field) {
            if ($data[$field] != $option->{$field}) {
                $option->{$field} = $data[$field];
            }
        }

        $option->save();

        return redirect(route('admin.attributes.attribute', $attribute['id']))->with('alert-success', 'The option has been updated successfully.');
    }

    /**
     * Admin - Remove Option
     * URL: /admin/attributes/{attribute}/option/{option} (DELETE)
     *
     * @param $attribute
     * @param $option
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($attribute, $option)
    {
        if ($option->inventoryItems() && $option->inventoryItems()->count() > 0) {
            return back()->with('alert-danger', 'This option cannot be removed because there are ' . $option->inventoryItems()->count() . ' inventory items associated with it. Please remove the inventory items first.');
        } else {
            $option->delete();

            return redirect(route('admin.attributes.attribute', $attribute['id']))->with('alert-success', 'The option has been removed successfully.');
        }
    }

}