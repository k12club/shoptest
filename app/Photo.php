<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'original_name',
        'product_id',
        'default',
    ];

    public function product() {
        return $this->belongsTo('App\Product');
    }
}
