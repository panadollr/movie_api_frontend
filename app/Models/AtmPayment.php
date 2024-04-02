<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtmPayment extends Model
{
    protected $table = 'atm_payments';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable =[
        'card_holder_name',
        'card_number',
        'expiration_date',
        'security_code'
    ];
}
