<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $table    = "payment_method";
    protected $fillable = [
        'id',
        'name',
        'is_active',
        'created_at',
        'updated_at'
    ];
}
