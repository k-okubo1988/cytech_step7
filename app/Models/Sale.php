<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Facades\DB;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
    ];

    public function product(){
        return $this -> belongsTo(Product::class);
    }
}
