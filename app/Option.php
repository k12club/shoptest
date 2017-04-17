<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'order',
        'attribute_id',
    ];

    /**
     * Retrieve the associated Attribute (Attribute - Option: One to Many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attribute() {
        return $this->belongsTo('App\Attribute');
    }

    /**
     * Inventory Items (Inventory - Option: Many to Many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inventoryItems() {
        return $this->belongsToMany('App\Inventory', 'inventory_option', 'option_id', 'inventory_id');
    }

    /**
     * Retrieve Products using this Option
     * 
     * @return array
     */
    public function products() {
        $products = [];
        $productIds = [];

        if ($this->inventoryItems()->count() > 0) {
            foreach ($this->inventoryItems as $inventoryItem) {
                if (!in_array($inventoryItem->product['id'], $productIds)) {
                    $products[] = $inventoryItem->product;
                    $productIds[] = $inventoryItem->product['id'];
                }
            }
        }

        return $products;
    }
}
