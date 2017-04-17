<?php

namespace App\Http\Controllers;

use App\Product;
use App\Inventory;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;

class CartController extends Controller {

    /**
     * Cart
     * URL: /shop/cart
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        // Cart Items
        $cartItems  = session()->get('cart.items');

        $total = 0;

        if (count($cartItems) > 0) {
            for ($i = 0; $i < count($cartItems); $i++) {
                $inventoryItem = Inventory::find($cartItems[$i]['inventory_id']);

                if ($inventoryItem) {
                    $cartItems[$i]['inventory_item'] = $inventoryItem;
                }

                $total += $cartItems[$i]['price'] * $cartItems[$i]['quantity'];
            }
        }

        return view('shop.cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add items to Cart
     * URL: /shop/cart/add (POST)
     *
     * Add an item with ID $id (with $quantity) to the current Cart
     * This will add the current item to the current session:
     * cart.items = [
     *    {
     *       'id': '<id>',
     *       'quantity': <quantity>,
     *       'sku': '<sku>'
     *    }
     * ]
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request) {
        $id = $request->input('id');

        // Find the Product
        $product = Product::find($id);

        if (!$product) {
            return back()->with('alert-danger', 'The requested product is not available.');
        }

        $quantity = intval($request->input('quantity'));
        $inventoryItemId = $request->input('inventory_id');

        $inventoryItem = Inventory::find($inventoryItemId);

        if (!$inventoryItem) {
            return back()->with('alert-danger', 'The requested inventory item is not available at the moment, please select other product options.');
        }

        if ($inventoryItem['stock'] <= 0) {
            return back()->with('alert-danger', 'This item is currently not in stock.');
        } else if ($inventoryItem['stock'] < $quantity) {
            return back()->with('alert-danger', 'There are only ' . $inventoryItem['stock']. ' items available for this item with the selected options, please reduce your quantity.');
        } else {
            // Initialize the "cart" in session if it's not set yet
            if (!session()->has('cart')) {
                session(['cart' => []]);
            }

            $items = session()->pull('cart.items', []);

            // Compute the item price
            $price = $inventoryItem['price'] != 0 &&
            $inventoryItem['price'] != $product['price'] &&
            ($product['price'] + $inventoryItem['price']) > 0 ?
                ($product['price'] + $inventoryItem['price']) : $product['price'];

            $newItem = [
                'product_id' => $id,
                'product_name' => $product['name'],
                'inventory_id' => $inventoryItem['id'],
                'price' => $price,
                'quantity' => $quantity,
                'stock' => $inventoryItem['stock'],
                'sku' => $inventoryItem['sku']
            ];

            // Walk through each tem in the cart
            for ($i = 0; $i < count($items); $i++) {
                // If there is an item in the cart that is the same as the selected item
                if ($items[$i]['product_id'] == $id) {
                    // If this cart item's inventory_id is the same as the selected item's inventory_id, update its quantity & price
                    if ($items[$i]['inventory_id'] == $inventoryItemId) {
                        $items[$i]['quantity'] += $quantity;
                        $items[$i]['price'] = $price;

                        unset($newItem);
                    }
                }
            }

            // Merge new item to the current cart
            if (isset($newItem)) {
                $items[] = $newItem;
            }

            session(['cart.items' => $items]);

            return redirect(route('shop.cart'))->with('alert-success', 'The item has been added to cart successfully.');
        }
    }

    /**
     * Update Cart
     * URL: /shop/cart/update (POST)
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request) {
        $data = $request->all();

        // If the request is to update items in the cart
        if (!empty($data['items']) && is_array($data['items'])) {
            $cartItems = session('cart.items');

            // If there are items from the request
            if (!empty($data['items'])) {

                // Walk through every item from the request
                foreach ($data['items'] as $inventoryItemId => $quantity) {
                    // Walk through each item in the cart
                    for ($i = 0; $i < count($cartItems); $i++) {
                        // If requested item exists in the current session
                        if ($cartItems[$i]['inventory_id'] == $inventoryItemId) {
                            // Remove item if its requested quantity is 0
                            if ($quantity == 0) {
                                array_splice($cartItems, $i, 1);
                            }
                            // Otherwise update item's quantity
                            else {
                                $cartItems[$i]['quantity'] = $quantity;
                            }
                            break;
                        }
                    }
                }

                // Now update the items in cart
                if (!empty($cartItems)) {
                    session(['cart.items' => $cartItems]);
                } else {
                    session()->forget('cart');
                }
            }
            // Reset cart if all items are removed
            else {
                session()->forget('cart');
            }
        }

        // If the request is to update shipping option in the cart
        if (!empty($data['shipping'])) {
            session(['cart.shipping' => $data['shipping']]);
        }

        // If the request is to update Pay Later option
        if (isset($data['pay_later'])) {
            if (config('custom.checkout.pay_later') && ($data['pay_later'] === true || $data['pay_later'] == 'true')) {
                session(['cart.pay_later' => true]);
            } else {
                session()->forget('cart.pay_later');
            }
        }

        $fees = CustomHelper::computeFees(session('cart'));

        return ['status' => 'success', 'fees' => $fees];
    }

    /**
     * Empty Cart
     * URL: /shop/cart/empty
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset() {
        session()->forget('cart');

        return redirect(route('shop.cart'))->with('alert-success', 'The cart is now empty.');
    }

}