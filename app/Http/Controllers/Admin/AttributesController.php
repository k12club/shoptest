<?php

namespace App\Http\Controllers\Admin;

use App\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttributesController extends Controller {

    /**
     * Admin - Attributes
     * URL: /admin/attributes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $attributes = Attribute::all();

        return view('admin.attributes.index', [
            'attributes' => $attributes
        ]);
    }

    /**
     * Admin - Store Attribute
     * URL: /admin/attributes (POST)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name'    => 'required|min:2|max:45|unique:attributes',
            'display' => 'required|in:select,radio',
        ]);

        $createdAttribute = Attribute::create($data);

        if ($createdAttribute) {
            return redirect(route('admin.attributes.attribute', $createdAttribute['id']))->with('alert-success', 'The attribute has been added successfully.');
        } else {
            return back()->with('alert-danger', 'The attribute cannot be added, please try again or contact the administrator.');
        }
    }

    /**
     * Admin - Update Attribute
     * URL: /admin/attributes/{attribute} (POST)
     *
     * @param Request $request
     * @param $attribute
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $attribute)
    {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name'    => 'required|min:2|max:45|unique:attributes,name,' .  $attribute['id'],
            'display' => 'required|in:select,radio',
        ]);

        foreach (['name', 'display'] as $field) {
            if ($data[$field] != $attribute->{$field}) {
                $attribute->{$field} = $data[$field];
            }
        }

        $result = $attribute->save();

        if ($result) {
            return redirect(route('admin.attributes.attribute', $attribute['id']))->with('alert-success', 'The attribute has been updated successfully.');
        } else {
            return back()->with('alert-danger', 'The attribute cannot be updated, please try again or contact the administrator.');
        }
    }

    /**
     * Admin - Remove Attribute
     * URL: /admin/attributes/{attribute} (DELETE)
     *
     * @param $attribute
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($attribute)
    {
        if ($attribute->options() && $attribute->options()->count() > 0) {
            return back()->with('alert-danger', 'This attribute cannot be removed because there are ' . $attribute->options()->count() . ' options associated with it. Please remove the associated options first.');
        } else {
            $attribute->delete();

            return redirect(route('admin.attributes'))->with('alert-success', 'The attribute has been removed successfully.');
        }
    }

}