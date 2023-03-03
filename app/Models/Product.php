<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $table    = "product";
    protected $fillable = [
        'id',
        'product_name',
        'price',
        'created_at',
        'updated_at'
    ];
}
