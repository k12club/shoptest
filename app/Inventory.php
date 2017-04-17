<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    protected $guarded = ['id'];

    protected $fillable = [
        'sku',
        'product_id',
        'price',
        'stock',
        'order'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'sku'        => 'string',
        'product_id' => 'integer',
        'price'      => 'float',
        'stock'      => 'integer',
        'order'      => 'integer',
    ];

    /**
     * Product (Product - Inventory: One to Many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo('App\Product');
    }

    /**
     * Options (Inventory - Option: Many to Many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function options() {
        return $this->belongsToMany('App\Option', 'inventory_option', 'inventory_id', 'option_id');
    }

    /**
     * Out of Stock Inventory Items
     *
     * @return mixed
     */
    public static function outOfStock() {
        return self::where('stock', '<=', 0);
    }
}
