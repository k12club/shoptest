<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'phone',
        'address_1',
        'address_2',
        'city',
        'state',
        'zipcode',
        'is_delivery',
        'is_billing',
        'default_delivery',
        'default_billing',
        'user_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_delivery' => 'integer',
        'is_billing' => 'boolean',
        'default_delivery' => 'boolean',
        'default_billing' => 'boolean',
    ];

    /**
     * Address belongs to a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Delivery Addresses
     *
     * @return mixed
     */
    public static function deliveryAddresses() {
        return self::where('is_delivery', true);
    }

    /**
     * Billing Addresses
     * 
     * @return mixed
     */
    public static function billingAddresses() {
        return self::where('is_billing', true);
    }
}
