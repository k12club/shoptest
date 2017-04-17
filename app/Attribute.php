<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'display',
    ];

    /**
     * Retrieve the associated Options (Attribute - Option: One to Many)
     *
     * @return mixed
     */
    public function options() {
        return $this->hasMany('App\Option')->orderBy('order');
    }

    /**
     * Retrieve the associated Products (Attribute - Product: Many to Many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products() {
        return $this->belongsToMany('App\Product');
    }
}
