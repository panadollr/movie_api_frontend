<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details';
    public $timestamps = false;
    protected $primaryKey = 'order_detail_id';
    protected $fillable =[
        'order_id',
        'product_id',
        'product_quantity',
    ];

}
