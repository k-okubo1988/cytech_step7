<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
        $sort = $request -> input('sort', 'product_name');
        $direction = $request -> input ('direction', 'asc');
        $keyword = $request -> input('keyword');
        $selectedCompanyId = $request -> input('company_id');

        $productsQuery = Product::with('company')
                -> sortable([$sort => $direction]);


        if( $keyword ){
            $productsQuery->where('product_name', 'LIKE', '%' . $keyword . '%');
        }
        if( $selectedCompanyId ){
            $productsQuery -> where('company_id', $selectedCompanyId);
        }
        
        $products = $productsQuery -> paginate(5);

        $companies = Company::all();

        return view('products.index', compact('products', 'companies','selectedCompanyId', 'keyword', 'sort'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search( request $request )
    {
        $sort = Product::sortable() -> get();
        // $input = $request -> all();
        $keyword = $request -> input('keyword');
        $selectedCompanyId = $request -> input('company_id');
        $minPrice = $request -> input('min_price');
        $maxPrice = $request -> input('max_price');
        $minStock = $request -> input('min_stock');
        $maxPrice = $request -> input('max_stock');

        $productsQuery = Product::with('company');


        if($keyword){
            $productsQuery -> where('product_name', 'LIKE', '%' . $keyword . '%');
        }

        if($selectedCompanyId){
            $productsQuery -> whereHas('company',function($query)use($selectedCompanyId){
                $query -> where('id', $selectedCompanyId);
            });
        }

        if($minPrice){
            $productsQuery -> where('price', '>=', $minPrice);
        }
        if($maxPrice){
            $productsQuery -> where('price', '<=', $maxPrice);
        }
        if($minStock){
            $productsQuery -> where('price', '>=', $minStock);
        }
        if($maxStock){
            $productsQuery -> where('price', '<=', $maxStock);
        }
        
        $products = $productsQuery ->with('company') -> paginate(5);

        $companies = Company::all();

        return response() -> json([
            'products' => $products -> items(),
            'companies' => $companies,
            'selectedCompanyId' => $selectedCompanyId,
            'keyword' => $keyword,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'minStock' => $minStock,
            'maxStock' => $maxStock,
            'sort' => $sort,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();

        return view('products.registration', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        try{
            DB::beginTransaction();

            $product = new Product();
            $product -> fill ($request -> all());
    
            if( $request -> hasFile('img_path')){
                $fileName = $request -> img_path -> getClientOriginalName();
                $filePath = $request -> img_path -> storeAs('products', $fileName, 'public');
                $product -> img_path = '/storage/' . $filePath;
            }

            $product -> save();

            DB::commit();

            return redirect() -> route('products.show', $product -> id)
                -> with('success', 'データの新規登録が完了しました。');

        } catch(\Exception $e) {
            DB::rollback();

            return redirect() -> back()
                -> withErrors($validator)
                -> withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.detail', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $companies = Company::all();

        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, Product $product)
    {
            try{
                DB::beginTransaction();

                if( $request -> hasFile('img_path')){
                    $fileName = $request -> img_path -> getClientOriginalName();
                    $filePath = $request -> img_path -> storeAs('products', $fileName, 'public');
                    $product -> img_path = '/storage/' . $filePath;
                }

                $product -> update($validatedDate);

                DB::commit();

                return redirect() -> route('products.show', $product -> id)
                    ->with('success', 'データを更新しました。');

            } catch(\Exception $e) {
                return back()
                    ->with('error', 'エラーが起きました。')
                    -> withInput();
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try{
            DB::beginTransaction();

            $product -> delete();

            DB::commit();

            return redirect('/products')
                ->with('success', '商品を削除しました。');
        } catch(\Exception $e) {
            DB::rollback();

            return back()
                ->with('error', '商品の削除に失敗しました。');
        }
    }
}
