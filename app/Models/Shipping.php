<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shippings';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable =[
        'order_id',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_note'
    ];

}
