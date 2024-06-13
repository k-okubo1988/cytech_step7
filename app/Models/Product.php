<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Product extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'img_path',
    ];

    public $sortable = ['id','product_name','price','stock', 'company_name'];

    public function sales(){
        return $this -> hasMany(Sale::class);
    }

    public function company(){
        return $this -> belongsTo(company::class);
    }
}
