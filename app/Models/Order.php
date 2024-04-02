<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = false;
    protected $primaryKey = 'order_id';
    protected $fillable =[
        'payment_method_id',
        'order_total',
        'order_status',
    ];

    public function movies()
    {
        // return $this->hasOne(Movie::class, '_id', '_id');
    }
}
