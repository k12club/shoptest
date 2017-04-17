<?php

namespace App\Http\Controllers\Admin;

use App\Option;
use App\Attribute;
use App\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller {

    /**
     * Admin - List Product Inventory Items
     * URL: /admin/inventory
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $inventoryItems = Inventory::orderBy('product_id')->orderBy('order')->orderBy('sku')->get();

        $attributes = Attribute::all()->toArray();

        $options = Option::orderBy('attribute_id')->orderBy('key')->get()->toArray();

        return view('admin.products.inventory.index', [
            'inventoryItems' => $inventoryItems,
            'attributes'     => array_reduce($attributes, function ($result, $item) {
                $result[$item['id']] = $item['value'];
                return $result;
            }, []),
            'options'        => array_reduce($options, function ($result, $item) {
                $result[$item['attribute_id']][] = $item;
                return $result;
            }, []),
        ]);
    }

    /**
     * Out of Stock Inventory Items
     * URL: /admin/products/out-of-stock
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function outOfStock() {
        $outOfStockItems = Inventory::outOfStock()->get();

        return view('admin.products.inventory.out_of_stock', compact('outOfStockItems'));
    }

    /**
     * Admin - Create Inventory Item
     * URL: /admin/{product}/inventory/create
     *
     * @param $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($product) {
        $attributes = Attribute::all();

        return view('admin.products.inventory.create', compact('product', 'attributes'));
    }

    /**
     * Admin - Store Inventory Item
     * URL: /admin/inventory/create (POST)
     *
     * @param Request $request
     * @param $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function store(Request $request, $product)
    {
        $data = $request->all();

        $data['product_id'] = $product['id'];

        // Validation
        $this->validate($request, [
            'sku'   => 'required|min:2|alpha_dash|unique:inventory,sku',
            'stock' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        $createdInventory = Inventory::create($data);

        if ($createdInventory) {
            if (isset($data['options']) && count($data['options']) > 0) {
                $createdInventory->options()->attach($data['options']);
            }

            return redirect(route('admin.products.product', $product['id']))->with('alert-success', 'The product inventory item has been added successfully.');
        } else {
            return back()->with('alert-danger', 'The product inventory item cannot be saved, please try again or contact the administrator.');
        }
    }

    /**
     * Admin - Product Inventory Item
     * URL: /admin/{product}/inventory/<inventoryItem>
     *
     * @param $product
     * @param $inventoryItem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($product, $inventoryItem) {
        $attributes = Attribute::all();

        return view('admin.products.inventory.show', compact('product', 'inventoryItem', 'attributes'));
    }

    /**
     * Admin - Update Inventory Item
     * URL: /admin/{product}/inventory/{inventoryItem} (POST)
     *
     * @param Request $request
     * @param $product
     * @param $inventoryItem
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Foundation\Validation\ValidationException
     */
    public function update(Request $request, $product, $inventoryItem) {
        $data = $request->all();

        // Validation
        $this->validate($request, [
            'sku'   => 'required|min:2|alpha_dash|unique:inventory,sku,' . $data['sku'] .',sku',
            'stock' => 'required|numeric',
            'price' => 'required|numeric'
        ]);

        foreach ([
                     'sku',
                     'price',
                     'stock',
                 ] as $field) {
            if ($data[$field] != $inventoryItem->{$field}) {
                $inventoryItem->{$field} = $data[$field];
            }
        }

        $inventoryItem->save();

        // Update options
        if (isset($data['options']) && count($data['options']) > 0) {
            $inventoryItem->options()->sync($data['options']);
        }

        return redirect(route('admin.products.product', $inventoryItem->product['id']))->with('alert-success', 'The product inventory item has been updated successfully.');
    }

    /**
     * Admin - Remove Inventory Item
     * URL: /admin/products/{product}/inventory/{inventoryItem} (DELETE)
     *
     * @param $product
     * @param $inventoryItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($product, $inventoryItem)
    {
        $inventoryItem->options()->detach();

        $inventoryItem->delete();

        return redirect(route('admin.products.product', $product['id']))->with('alert-success', 'The product inventory item has been removed successfully.');
    }

}