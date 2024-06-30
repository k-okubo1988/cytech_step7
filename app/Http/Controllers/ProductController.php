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
        $sortField = $request->input('sortField', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');
        $keyword = $request -> input('keyword');
        // $selectedCompanyId = $request -> input('company_id');

        $products = Product::with('company')
            ->orderBy($sortField, $sortDirection)
            ->paginate(5);

        $companies = Company::all();

        return view('products.index', compact('products', 'companies', 'sortField', 'sortDirection'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search( request $request )
    {
        $sortField = $request->input('sortField', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');
        $keyword = $request->input('keyword');
        $selectedCompanyId = $request->input('company_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minStock = $request->input('min_stock');
        $maxStock = $request->input('max_stock');

        $query = Product::with('company');

        if ($request->has('keyword') && $request->keyword) {
            $query->where('product_name', 'LIKE', '%' . $request->keyword . '%');
        }

        if ($request->has('company_id') && $request->company_id) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('id', $request->company_id);
            });
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('min_stock') && $request->min_stock) {
            $query->where('stock', '>=', $request->min_stock);
        }

        if ($request->has('max_stock') && $request->max_stock) {
            $query->where('stock', '<=', $request->max_stock);
        }

        $products = $query->orderBy($sortField, $sortDirection)->paginate(5);
        $companies = Company::all();

        return response()->json([
            'products' => $products->items(),
            'companies' => $companies,
            'selectedCompanyId' => $request->company_id,
            'keyword' => $request->keyword,
            'minPrice' => $request->min_price,
            'maxPrice' => $request->max_price,
            'minStock' => $request->min_stock,
            'maxStock' => $request->max_stock,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
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

                $product -> fill($request -> validated()) -> save();

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
