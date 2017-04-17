<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $guarded = ['id'];

    protected $fillable = [
        'order_number',
        'confirmation_code',
        'token',
        'shipping_carrier',
        'shipping_plan',
        'shipping_fee',
        'shipping_tracking_number',
        'subtotal',
        'tax',
        'total',
        'user_id',
        'contact_email',
        'delivery_name',
        'delivery_address_1',
        'delivery_address_2',
        'delivery_city',
        'delivery_state',
        'delivery_zipcode',
        'delivery_country',
        'delivery_phone',
        'billing_name',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_zipcode',
        'billing_country',
        'billing_phone',
        'notes',
        'pay_later',
        'cash_on_delivery',
        'status',
        'payment_status',
        'payment_method',
        'stripe_charge_id',
        'stripe_refund_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'pay_later' => 'boolean',
        'cash_on_delivery' => 'boolean',
    ];

    /**
     * Inventory Items of an Order (Order - Inventory: Many to Many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inventoryItems() {
        return $this->belongsToMany('App\Inventory', 'inventory_order', 'order_id', 'inventory_id')->withPivot('product_name', 'price', 'quantity', 'sku');
    }

    /**
     * User (if logged in) who placed the order (Order - User: One to One)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
}