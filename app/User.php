<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'type',
        'stripe_customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * User has many Addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses() {
        return $this->hasMany('App\Address');
    }

    /**
     * Delivery Addresses of a User
     *
     * @return mixed
     */
    public function deliveryAddresses() {
        return $this->hasMany('App\Address')->where('is_delivery', true);
    }

    /**
     * Billing Addresses of a User
     *
     * @return mixed
     */
    public function billingAddresses() {
        return $this->hasMany('App\Address')->where('is_billing', true);
    }

    /**
     * Default delivery Address of a User
     *
     * @return mixed
     */
    public function deliveryAddress() {
        return $this->hasMany('App\Address')->where('default_delivery', true);
    }

    /**
     * Default billing Address of a User
     *
     * @return mixed
     */
    public function billingAddress() {
        return $this->hasMany('App\Address')->where('default_billing', true);
    }

    /**
     *  User has many cards
     *
     * @return mixed
     */
    public function cards() {
        return $this->hasMany('App\PaymentSource')->where('type', 'card');
    }

    /**
     * User has maximum 1 primary card
     *
     * @return mixed
     */
    public function primaryCard() {
        return $this->hasOne('App\PaymentSource')->where(['type' => 'card', 'default' => true]);
    }

    /**
     * Orders this User made
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany('App\Order');
    }
}