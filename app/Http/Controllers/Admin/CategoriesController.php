<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller {

    /**
     * Admin - Store Category
     * URL: /admin/categories (POST)
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name' => 'required|min:2|max:45|unique:categories',
            'uri' => 'required|min:2|max:45|alpha_dash|unique:categories',
        ]);

        $createdCategory = Category::create($data);

        if ($createdCategory) {
            return redirect(route('admin.categories'))->with('alert-success', 'The category has been added successfully.');
        } else {
            return back()->with('alert-danger', 'The category cannot be added, please try again or contact the administrator.');
        }
    }

    /**
     * Admin - Update category
     * URL: /admin/categories/{category} (POST)
     *
     * @param Request $request
     * @param $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $category)
    {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name' => 'required|min:2|max:45|unique:categories,name,' .  $category['id'],
            'uri' => 'required|min:2|max:45|alpha_dash|unique:categories,uri,' .  $category['id'],
        ]);

        foreach (['name', 'uri'] as $field) {
            if ($data[$field] != $category->{$field}) {
                $category->{$field} = $data[$field];
            }
        }

        $result = $category->save();

        if ($result) {
            return redirect(route('admin.categories.category', $category['id']))->with('alert-success', 'The category has been updated successfully.');
        } else {
            return back()->with('alert-danger', 'The category cannot be updated, please try again or contact the administrator.');
        }
    }

    /**
     * Admin - Remove Category
     * URL: /admin/categories/{category} (DELETE)
     *
     * @param $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($category)
    {
        $category->delete();

        return redirect(route('admin.categories'))->with('alert-success', 'The category has been removed successfully.');
    }

}