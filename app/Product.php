<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'uri',
        'description',
        'price',
        'old_price',
        'category_id',
        'special',
        'new',
        'order',
    ];

    /**
     * Product's Photos
     *
     * @return mixed
     */
    public function photos() {
        return $this->hasMany('App\Photo')->orderBy('order');
    }

    /**
     * Product's default Photo
     *
     * @return mixed
     */
    public function defaultPhoto() {
        return $this->hasOne('App\Photo')->where('default', true);
    }

    /**
     * Category this Product belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo('App\Category');
    }

    /**
     * Orders had this Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders() {
        return $this->belongsToMany('App\Order');
    }

    /**
     * Inventory Items of a Product (Product - Inventory Item: One to Many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventoryItems() {
        return $this->hasMany('App\Inventory');
    }

    /**
     * Check if a Product is in stock (if this Product has any belonging Inventory Item that has their stock positive
     *
     * @return bool
     */
    public function inStock() {
        if ($this->inventoryItems() && $this->inventoryItems()->count() > 0) {
            foreach ($this->inventoryItems as $inventoryItem) {
                if ($inventoryItem['stock'] > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if this Product has Out of Stock Inventory Items
     *
     * @return bool
     */
    public function hasOutOfStock() {
        if ($this->inventoryItems() && $this->inventoryItems()->count() > 0) {
            foreach ($this->inventoryItems as $inventoryItem) {
                if ($inventoryItem['stock'] <= 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Array of available Attributes and Options of this Product
     *
     * @return array
     */
    public function availableOptions() {
        $availableOptions = [];

        foreach ($this->inventoryItems()->where('stock', '>', 0)->get() as $availableInventoryItem) {
            foreach ($availableInventoryItem->options as $availableInventoryItemOption) {
                if (!isset($availableOptions[$availableInventoryItemOption->attribute['id']])) {
                    $availableOptions[$availableInventoryItemOption->attribute['id']] = [
                        'name' => $availableInventoryItemOption->attribute['name'],
                        'display' => $availableInventoryItemOption->attribute['display'],
                        'options' => [
                            $availableInventoryItemOption['id'] => [
                                'name' => $availableInventoryItemOption['name'],
                                'price' => floatval($availableInventoryItem->product['price'] + $availableInventoryItem['price']),
                                'count' => $availableInventoryItem['stock'],
                            ]
                        ]
                    ];
                } else {
                    if (!isset($availableOptions[$availableInventoryItemOption->attribute['id']]['options'][$availableInventoryItemOption['id']])) {
                        $availableOptions[$availableInventoryItemOption->attribute['id']]['options'][$availableInventoryItemOption['id']] = [
                            'name' => $availableInventoryItemOption['name'],
                            'price' => floatval($availableInventoryItem->product['price'] + $availableInventoryItem['price']),
                            'count' => $availableInventoryItem['stock']
                        ];
                    } else {
                        $availableOptions[$availableInventoryItemOption->attribute['id']]['options'][$availableInventoryItemOption['id']]['count'] += $availableInventoryItem['stock'];
                    }
                }
            }
        }

        return $availableOptions;
    }

    /**
     * Array of available inventory items, along with their options, for this Product
     *
     * @return array
     */
    public function availableInventoryItems() {
        $availableInventoryItems = [];

        foreach ($this->inventoryItems()->where('stock', '>', 0)->get() as $availableInventoryItem) {
            $item = [
                'inventory' => $availableInventoryItem['id'],
                'price' => number_format($availableInventoryItem->product['price'] + $availableInventoryItem['price'], 2),
                'options' => []
            ];

            if ($availableInventoryItem->options()->count() > 0) {
                foreach ($availableInventoryItem->options as $availableInventoryItemOption) {
                    $item['options'][] = [
                        'attribute' => $availableInventoryItemOption->attribute['id'],
                        'option' => $availableInventoryItemOption['id']
                    ];
                }
            }

            $availableInventoryItems[] = $item;
        }

        return $availableInventoryItems;
    }
}