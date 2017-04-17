<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

// Homepage
Route::get('/', 'HomeController@index')->name('home');

// Shop
Route::group(['prefix' => 'shop', 'as' => 'shop'], function() {

    // Home - URL: /shop
    Route::get('/', 'ShopController@index');

    // Category - URL: /shop/c/{uri}
    Route::get('c/{uri}', 'ShopController@category')->where('uri', '[0-9a-z\-]+')->name('.category');

    // Product - URL: /shop/p/{uri}-{product}.html
    Route::get('p/{uri}-{product}.html', function($uri, $product) {
        return view('shop.products.show', compact('product'));
    })->where([
        'uri' => '[0-9a-z\-]+',
        'product' => '[0-9]+'
    ])->name('.product');

    // Checkout
    Route::group(['prefix' => 'checkout', 'as' => '.checkout'], function() {
        // Checkout - URL: /shop/checkout
        Route::get('/', 'ShopController@checkout');

        // Submit Checkout - URL: /shop/checkout (POST)
        Route::post('/', 'ShopController@checkoutSubmit')->name('.submit');

        // Login at Checkout - URL: /shop/checkout/login (POST)
        Route::post('login', 'ShopController@login')->name('.login');

        // Confirmation - URL: /shop/confirmation/{order_number}/{confirmation_code}
        Route::get('confirmation/{order_number}/{confirmation_code}', function($orderNumber, $confirmationCode) {
            return view('shop.checkout.confirmation', [
                'orderNumber' => $orderNumber,
                'confirmationCode' => $confirmationCode
            ]);
        })->where([
            'orderNumber' => '[0-9]+',
            'confirmationCode' => '[A-Z0-9]{6}'
        ])->name('.confirmation');
    });

    // Cart
    Route::group(['prefix' => 'cart', 'as' => '.cart'], function() {
        // Cart - URL: /shop/cart
        Route::get('/', 'CartController@index');

        // Add item to cart: URL: /shop/cart/add (POST)
        Route::post('add', 'CartController@add')->name('.add');

        // Update Cart - URL: /shop/cart/update (POST)
        Route::post('update', 'CartController@update')->name('.update');

        // Empty Cart - URL: /shop/cart/empty
        Route::get('empty', 'CartController@reset')->name('.empty');
    });

    // Addresses
    Route::group(['prefix' => 'addresses', 'as' => '.addresses'], function() {
        // Create/edit address - URL: /shop/addresses/manage (POST)
        Route::post('manage', 'AddressesController@manage')->name('.manage');
    });

    // Payments
    Route::group(['prefix' => 'payments', 'as' => '.payments'], function() {
        // Store payment source - URL: /shop/payments/create (POST)
        Route::post('/', 'PaymentsController@store')->name('.store');
    });
});

// Users
Route::group(['middleware' => ['auth']], function() {
    // Account - URL: /account
    Route::get('account', function() {
        return view('users.account');
    })->name('account');

    // Update Account - URL: /account (POST)
    Route::post('account', 'UsersController@update')->name('account.update');

    // Orders
    Route::group(['prefix' => 'orders'], function() {
        // Orders - URL: /orders
        Route::get('/', function() {
            return view('users.orders.index');
        })->name('orders');

        // Order - URL: /{orderNumber}
        Route::get('{orderNumber}', 'UsersController@order')->where('orderNumber', '[0-9]+')->name('user_order');
    });

    // Addresses
    Route::group(['prefix' => 'addresses', 'as' => 'addresses'], function() {
        // Addresses - URL: /addresses
        Route::get('/', function() {
            return view('users.addresses.index');
        });

        // Create Address - URL: /addresses/create
        Route::get('create', function() {
            return view('users.addresses.create');
        })->name('.create');

        // Store Address - URL: /addresses (POST)
        Route::post('/', 'AddressesController@store')->name('.store');

        // Edit Address - URL: /addresses/{address}/edit
        Route::get('{address}/edit', function ($address) {
            if (auth()->user()->can('view', $address)) {
                return view('users.addresses.edit', compact('address'));
            } else {
                return redirect(route('addresses'))->with('message', 'danger|You do not have permission to view this address.');
            }
        })->where('address', '[0-9]+')->name('.edit');

        // Update Address - URL: /addresses/{address} (POST)
        Route::post('{address}', 'AddressesController@update')->name('.update');

        // Set Address primary - URL: /addresses/{address}/primary
        Route::get('{address}/primary', 'AddressesController@primary')->name('.primary');

        // Remove Address - URL: /addresses/{address} (DELETE)
        Route::delete('{address}', 'AddressesController@destroy')->name('.remove');
    });

    // Payments
    Route::group(['prefix' => 'payments', 'as' => 'payments'], function() {
        // Payment Sources - URL: /payments
        Route::get('/', function() {
            return view('users.payments.index');
        });

        // Create Payment Source - URL: /payments/create
        Route::get('create', function() {
            return view('users.payments.create');
        })->name('.create');

        // Store Payment Source - URL: /payments (POST)
        Route::post('/', 'PaymentsController@store')->name('.store');

        // Edit Payment Source - URL: /payments/{paymentSource}/edit
        Route::get('{paymentSource}/edit', function($paymentSource) {
            if (auth()->user()->can('view', $paymentSource)) {
                return view('users.payments.edit', compact('paymentSource'));
            } else {
                return redirect(route('payments'))->with('message', 'danger|You do not have permission to view this payment source.');
            }
        })->where('address', '[0-9]+')->name('.edit');

        // Update Payment Source - URL: /payments/{paymentSource} (POST)
        Route::post('{paymentSource}', 'PaymentsController@update')->name('.update');

        // Remove Payment Source - URL: /payments/{paymentSource} (DELETE)
        Route::delete('{paymentSource}', 'PaymentsController@destroy')->name('.remove');
    });
});

// Static Pages
Route::group(['as' => 'page.'], function() {
    // About page - URL: /about
    Route::get('about', function() {
        return view('pages.about');
    })->name('about');

    // Services page - URL: /services
    Route::get('services', function() {
        return view('pages.services');
    })->name('services');

    // Contact page - URL: /contact
    Route::get('contact', function() {
        return view('pages.contact');
    })->name('contact');

    // Contact page - Send message - URL: /contact (POST)
    Route::post('contact', 'PagesController@contactSend')->name('send_contact');

    // Terms page - URL: /terms
    Route::get('terms', function() {
        return view('pages.terms');
    })->name('terms');

    // Delivery page - URL: /delivery
    Route::get('delivery', function() {
        return view('pages.delivery');
    })->name('delivery');
});

// Search
Route::get('/search', 'SearchController@index')->name('search');

// Sitemap
Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap');

// Sitemap
Route::group(['prefix' => 'sitemap', 'as' => 'sitemap.'], function() {
    Route::get('pages.xml', 'SitemapController@pages')->name('pages');

    Route::get('c/{uri}.xml', 'SitemapController@category')->name('category');
});

// Admin
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin']], function() {
    // Dashboard - URL: /admin
    Route::get('/', 'HomeController@index')->name('home');

    // Orders
    Route::group(['prefix' => 'orders', 'as' => 'orders'], function() {
        // Order - URL: /admin/orders
        Route::get('/', 'OrdersController@index');

        // Order - URL: /admin/orders/{order}
        Route::get('{order}', function($order) {
            return view('admin.orders.show', compact('order'));
        })->where('order', '[0-9]+')->name('.order');

        // Edit Order: URL: /admin/orders/{order}/edit
        Route::get('{order}/edit', function($order) {
            return view('admin.orders.edit', compact('order'));
        })->where('order', '[0-9]+')->name('.edit');

        // Update Order - URL: /admin/orders/{order} (POST)
        Route::post('{order}', 'OrdersController@update')->where('order', '[0-9]+')->name('.update');

        // Send Order Email - URL: /admin/orders/email/{type}
        Route::get('{order}/email/{type}', 'OrdersController@email')->where(['order' => '[0-9]+'])->name('.send_email');
    });

    // Categories
    Route::group(['prefix' => 'categories', 'as' => 'categories'], function() {
        // Categories - URL: /admin/categories
        Route::get('/', function() {
            return view('admin.categories.index');
        });

        // Create Category - URL: /admin/categories/create
        Route::get('create', function() {
            return view('admin.categories.create');
        })->name('.create');

        // Store Category - URL: /admin/categories (POST)
        Route::post('/', 'CategoriesController@store')->name('.store');

        // Category - URL: /admin/categories/{category}
        Route::get('{category}', function($category) {
            return view('admin.categories.show', compact('category'));
        })->where('category', '[0-9]+')->name('.category');

        // Update Category - URL: /admin/categories/{category} (POST)
        Route::post('{category}', 'CategoriesController@update')->where('category', '[0-9]+')->name('.update');

        // Remove Category - URL: /admin/categories/{category} (DELETE)
        Route::delete('{category}', 'CategoriesController@destroy')->where('category', '[0-9]+')->name('.remove');
    });

    // Attributes
    Route::group(array('prefix' => 'attributes', 'as' => 'attributes'), function() {
        // Attributes - URL: /admin/attributes
        Route::get('/', 'AttributesController@index');

        // Create Attribute - URL: /admin/attributes/create
        Route::get('create', function() {
            return view('admin.attributes.create');
        })->name('.create');

        // Store Attribute - URL: /admin/attributes (POST)
        Route::post('/', 'AttributesController@store')->name('.store');

        // Attribute - URL: /admin/attributes/{attribute}
        Route::get('{attribute}', function($attribute) {
            return view('admin.attributes.show', compact('attribute'));
        })->where('attribute', '[0-9]+')->name('.attribute');

        // Update Attribute - URL: /admin/attributes/{attribute} (POST)
        Route::post('{attribute}', 'AttributesController@update')->where('attribute', '[0-9]+')->name('.update');

        // Remove Attribute - URL: /admin/attributes/{attribute} (DELETE)
        Route::delete('{attribute}', 'AttributesController@destroy')->where('attribute', '[0-9]+')->name('.remove');

        // Attribute
        Route::group(array('prefix' => '{attribute}', 'as' => '.attribute.'), function() {
            // Options
            Route::group(array('prefix' => 'options', 'as' => 'options.'), function() {
                // Create Option - URL: /admin/attributes/{attribute}/options/create
                Route::get('create', function($attribute) {
                    return view('admin.attributes.options.create', compact('attribute'));
                })->where('attribute', '[0-9]+')->name('create');

                // Store Option - URL: /admin/attributes/{attribute}/options (POST)
                Route::post('/', 'OptionsController@store')->where('attribute', '[0-9]+')->name('store');

                // Option - URL: /admin/attributes/{attribute}/options/{option}
                Route::get('{option}', function($attribute, $option) {
                    return view('admin.attributes.options.show', compact('attribute', 'option'));
                })->where([
                    'attribute' => '[0-9]+',
                    'option' => '[0-9]+'
                ])->name('option');

                // Update Option - URL: /admin/attributes/{attribute}/options/{option} (POST)
                Route::post('{option}', 'OptionsController@update')
                    ->where([
                        'attribute' => '[0-9]+',
                        'option' => '[0-9]+'
                    ])->name('update');

                // Remove Option - URL: /admin/attributes/{attribute}/options/{option} (DELETE)
                Route::delete('{option}', 'OptionsController@destroy')
                    ->where([
                        'attribute' => '[0-9]+',
                        'option' => '[0-9]+'
                    ])->name('remove');
            });
        });
    });

    // Products
    Route::group(array('prefix' => 'products', 'as' => 'products'), function() {
        // Product - URL: /admin/products
        Route::get('/', 'ProductsController@index');

        // Product - URL: /admin/products/out-of-stock
        Route::get('out-of-stock', 'InventoryController@outOfStock')->name('.out_of_stock');

        // Create Product - URL: /admin/products/create
        Route::get('create', function() {
            return view('admin.products.create');
        })->name('.create');

        // Store Product - URL: /admin/products (POST)
        Route::post('/', 'ProductsController@store')->name('.store');

        // Product - URL: /admin/products/{product}
        Route::get('{product}', function($product) {
            return view('admin.products.show', compact('product'));
        })->where('product', '[0-9]+')->name('.product');

        // Update Product - URL: /admin/products/{product} (POST)
        Route::post('{product}', 'ProductsController@update')->where('product', '[0-9]+')->name('.update');

        // Remove Product - URL: /admin/products/{product} (DELETE)
        Route::delete('{product}', 'ProductsController@destroy')->where('product', '[0-9]+')->name('.remove');

        // Product
        Route::group(['prefix' => '{product}', 'as' => '.product.'], function() {
            // Inventory
            Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function() {
                // Create Inventory Item - URL: /admin/products/{product}/inventory/create
                Route::get('create', 'InventoryController@create')->name('create');

                // Create Inventory Item - URL: /admin/products/{product}/inventory (POST)
                Route::post('/', 'InventoryController@store')->name('store');

                // Inventory Item - URL: /admin/products/{product}/inventory/{inventoryItem}
                Route::get('{inventoryItem}', 'InventoryController@show')
                    ->where([
                        'product' => '[0-9]+',
                        'inventoryItem' => '[0-9]+'
                    ])
                    ->name('inventory');

                // Update Inventory Item - URL: /admin/products/{product}/inventory/{inventoryItem} (POST)
                Route::post('{inventoryItem}', 'InventoryController@update')
                    ->where([
                        'product' => '[0-9]+',
                        'inventoryItem' => '[0-9]+'
                    ])
                    ->name('update');

                // Remove Inventory Item - URL: /admin/products/{product}/inventory/{inventoryItem} (DELETE)
                Route::delete('{inventoryItem}', 'InventoryController@destroy')
                    ->where([
                        'product' => '[0-9]+',
                        'inventoryItem' => '[0-9]+'
                    ])
                    ->name('remove');
            });
        });
    });

    // Users
    Route::group(array('prefix' => 'users', 'as' => 'users'), function() {
        // Users - URL: /users
        Route::get('/', 'UsersController@index');

        // User - URL: /users/{user}
        Route::get('{user}', function($user) {
            return view('admin.users.show', compact('user'));
        })->name('.user');

        // Update User - URL: /users/{user} (POST)
        Route::post('{user}', 'UsersController@update')->where('user', '[0-9]+')->name('.update');
    });
});