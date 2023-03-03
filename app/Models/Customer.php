<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $table    = "customer";
    protected $fillable = [
        'id',
        'customer_name',
        'created_at',
        'updated_at'
    ];
}
