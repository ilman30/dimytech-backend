<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $table    = "order";
    protected $fillable = [
        'id',
        'order_date',
        'total',
        'customer',
        'payment_method_id',
        'created_at',
        'updated_at'
    ];
}
