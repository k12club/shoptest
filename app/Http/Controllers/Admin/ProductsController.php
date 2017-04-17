<?php

namespace App\Http\Controllers\Admin;

use File;
use Cache;
use Image;
use Storage;
use Validator;
use App\Photo;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductsController extends Controller {

    /**
     * Admin - Products
     * URL: /admin/products
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::orderBy('category_id')
            ->orderBy('order')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Admin - Store Product
     * URL: /admin/products (POST)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Pre-sanitize input data
        $request->merge(['price' => str_replace(',', '', $request->get('price'))]); // Remove comma if any
        $request->merge(['old_price' => str_replace(',', '', $request->get('old_price'))]); // Remove comma if any

        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name' => 'required|min:2|max:255|unique:products',
            'uri' => 'required|min:2|max:255|alpha_dash|unique:products',
            'price' => 'required|numeric',
            'old_price' => 'sometimes|numeric',
            'order' => 'required|numeric',
        ]);

        // Sanitize input data
        $data['special'] = isset($data['special']) && $data['special'] == 'on';
        $data['new'] = isset($data['new']) && $data['new'] == 'on';
        if (isset($data['old_price']) && empty($data['old_price'])) {
            unset($data['old_price']);
        }

        $createdProduct = Product::create($data);

        // Initiate Storage
        $s3 = Storage::disk('s3');

        // If Product record is created
        if ($createdProduct) {
            // Photos - Upload new photos
            if ($request->hasFile('photos')) {
                if (env('SHOP_DEMO')) {
                    return redirect(route('admin.products.product', $createdProduct['id']))->with('alert-success', 'The product has been added successfully, but photo upload is not available in this demo mode.');
                }

                $files = $request->file('photos');

                // For each photo uploaded
                foreach ($files as $index => $file) {
                    // Validating each file
                    $validator = Validator::make(['file' => $file], ['file' => 'mimes:jpeg,png']);

                    if ($validator->passes()) {
                        $extension = $file->getClientOriginalExtension();
                        $fileName = str_random(10) . '.' . $extension;

                        // Upload photo to S3
                        $s3->put('photos/' . $fileName, file_get_contents($file), 'public');

                        // Make a thumbnail photo from the original photo
                        $image = Image::make($file)->resize(300, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save();

                        // Upload thumbnail photo to S3
                        $s3->put('photos/thumbnails/' . $fileName, file_get_contents($file), 'public');

                        // Create the Photo record
                        $photo = [
                            'name' => $fileName,
                            'original_name' => $file->getClientOriginalName(),
                            'product_id' => $createdProduct['id']
                        ];

                        // Set the first uploaded photo as default
                        if ($index == 0) {
                            $photo['default'] = true;
                        }

                        Photo::create($photo);
                    }
                    else {
                        // Redirect back with errors.
                        return back()->withErrors($validator);
                    }
                }
            }

            // Remove Cache
            Cache::flush();

            return redirect(route('admin.products.product', $createdProduct['id']))->with('alert-success', 'The product has been added successfully.');
        } else {
            return back()->with('alert-danger', 'The product cannot be added, please try again or contact the administrator.');
        }
    }

    /**
     * Admin - Update Product
     * URL: /admin/products/{product} (POST)
     *
     * @param Request $request
     * @param $product
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $product) {

        $photosData = [
            'photos_default' => $request->input('photos_default'),
            'photos_remove'  => $request->input('photos_remove'),
        ];

        // Pre-sanitize input data
        $request->merge(['price' => str_replace(',', '', $request->get('price'))]); // Remove comma if any
        $request->merge(['old_price' => str_replace(',', '', $request->get('old_price'))]); // Remove comma if any

        $data = $request->all();

        // Validation
        $this->validate($request, [
            'name' => 'required|min:2|max:255|unique:products,name,' . $product['id'],
            'uri' => 'required|min:2|max:255|alpha_dash|unique:products,uri,' . $product['id'],
            'price' => 'required|numeric',
            'old_price' => 'sometimes|numeric',
            'order' => 'required|numeric',
        ]);

        // Sanitize input data
        $data['special'] = isset($data['special']) && $data['special'] == 'on';
        $data['new'] = isset($data['new']) && $data['new'] == 'on';

        $photos = Photo::where('product_id', $product['id'])->get();

        // Initiate Storage
        $s3 = Storage::disk('s3');

        // Photos - Update new default
        if (!empty($photosData['photos_default'])) {
            Photo::where('product_id', $product['id'])
                ->update(array('default' => false));
            Photo::where('id', $photosData['photos_default'])
                ->update(array('default' => true));
        }

        if (!empty($photosData['photos_remove'])) {
            foreach ($photosData['photos_remove'] as $photoRemoveId) {
                foreach ($photos as $photo) {
                    if ($photo['id'] == $photoRemoveId) {

                        if (!env('SHOP_DEMO')) {
                            // Remove the photos to be removed
                            if ($s3->exists('photos/' . $photo['name'])) {
                                $s3->delete('photos/' . $photo['name']);
                            }

                            if ($s3->exists('photos/thumbnails/' . $photo['name'])) {
                                $s3->delete('photos/thumbnails/' . $photo['name']);
                            }
                        }
                    }

                    Photo::find($photo['id'])->delete();
                }
            }
        }

        // Photos - Upload new photos
        if ($request->hasFile('photos')) {
            if (env('SHOP_DEMO')) {
                foreach ([
                             'name',
                             'uri',
                             'description',
                             'price',
                             'old_price',
                             'category_id',
                             'special',
                             'new',
                             'order'
                         ] as $field) {
                    if ($data[$field] != $product->{$field}) {
                        $product->{$field} = $data[$field];
                    }
                }

                $product->save();

                return redirect(route('admin.products.product', $product['id']))->with('alert-success', 'The product has been updated successfully, but photo upload is not available in this demo mode.');
            }

            $files = $request->file('photos');

            // For each photo uploaded
            foreach ($files as $index => $file) {
                // Validating each file
                $validator = Validator::make(['file' => $file], ['file' => 'mimes:jpeg,png']);

                if ($validator->passes()) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName = str_random(10) . '.' .$extension;

                    // Upload photo to S3
                    $s3->put('photos/' . $fileName, file_get_contents($file), 'public');

                    // Make a thumbnail photo from the original photo
                    $image = Image::make($file)->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save();

                    // Upload thumbnail photo to S3
                    $s3->put('photos/thumbnails/' . $fileName, file_get_contents($file), 'public');

                    // Create the Photo record
                    $photo = [
                        'name' => $fileName,
                        'original_name' => $file->getClientOriginalName(),
                        'product_id' => $product['id']
                    ];

                    // If this product has no default photo yet, set the first uploaded photo as default
                    if ($index == 0 && $product->defaultPhoto()->count() == 0) {
                        $photo['default'] = true;
                    }

                    Photo::create($photo);
                }
                else {
                    // Redirect back with errors.
                    return back()->withErrors($validator);
                }
            }
        }

        foreach ([
                    'name',
                    'uri',
                    'description',
                    'price',
                    'old_price',
                    'category_id',
                    'special',
                    'new',
                    'order'
                 ] as $field) {
            if ($data[$field] != $product->{$field}) {
                $product->{$field} = $data[$field];
            }
        }

        $product->save();

        // Remove Cache
        Cache::flush();

        return redirect(route('admin.products.product', $product['id']))->with('alert-success', 'The product has been updated successfully.');
    }

    /**
     * Admin - Remove Product
     * URL: /admin/product/{product} (DELETE)
     *
     * @param $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($product)
    {
        if ($product->inventoryItems() && $product->inventoryItems()->count() > 0) {
            return back()->with('alert-danger', 'This product cannot be removed because there are ' . $product->inventoryItems()->count() . ' inventory items associated with it. Please remove the inventory items first.');
        } elseif ($product->photos() && $product->photos()->count() > 0) {
            return back()->with('alert-danger', 'This product cannot be removed because there are ' . $product->photos()->count() . ' photos associated with it. Please remove the photos first.');
        } else {
            $product->delete();

            return redirect(route('admin.products'))->with('alert-success', 'The product has been removed successfully.');
        }
    }

}
