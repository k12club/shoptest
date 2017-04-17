<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentSource extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'vendor',
        'name_on_card',
        'last4',
        'brand',
        'type',
        'user_id',
        'default',
        'vendor_card_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * Payment Source belongs to a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
}
