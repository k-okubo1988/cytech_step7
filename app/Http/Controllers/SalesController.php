<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        try{
            DB::beginTransaction();
            
            $productId = $request -> input('product_id');
            $product = Product::find($productId);

            if(!$product) {
                DB::rollback();
                return response() -> json(['error' => '商品が存在しません']);
            }
            if($product -> stock === 0){
                DB::rollback();
                return response() -> json(['error' => '商品が在庫不足です']);
            }

            $product -> decrement('stock');
            $product -> save();
    
            $sale = $product -> sales() -> create();
            $sale -> product_id = $product -> id;
            $sale -> save();
    
            DB::commit();
            return response() -> json(['message' => '購入成功']);

        }catch(\Exception $e){
            DB::rollback();
            return ['error' => '購入処理に失敗しました'];
        }


    }
}
