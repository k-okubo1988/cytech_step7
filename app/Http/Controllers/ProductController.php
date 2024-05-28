<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( request $request )
    {
        $keyword = $request -> input('keyword');
        $selectedCompanyId = $request -> input('company_id');
        
        $productsQuery = Product::query();
        $companies = Company::all();

        if( $keyword ){
            $productsQuery->where('product_name', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('comment', 'LIKE', '%' . $keyword . '%');
        }

        if( $selectedCompanyId ){
            $productsQuery -> where('company_id', $selectedCompanyId);
        }

        $products = $productsQuery -> paginate(5);

        return view('products.index', compact('products', 'keyword', 'companies', 'selectedCompanyId'));
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
    public function store(Request $request)
    {
        $request -> validate ([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image',
        ]);
        
        $product = new Product();
        $product -> fill ($request -> all());

        if( $request -> hasFile('img_path')){
            $fileName = $request -> img_path -> getClientOriginalName();
            $filePath = $request -> img_path -> storeAs('products', $fileName, 'public');
            $product -> img_path = '/storage/' . $filePath;
        }

        if($product -> save()){
            return redirect() -> route('products.show', $product -> id)
                -> with('success', 'データの新規登録が完了しました。');
        } else {
            return redirect() -> back()
                -> with('error', 'エラーが発生しました。')
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
        return view('products.detail', ['product' => $product]);
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
    public function update(Request $request, Product $product)
    {
            $validatedDate = $request -> validate([
                'product_name' => 'required',
                'company_id' => 'required',
                'price' => 'required',
                'stock' => 'required',
                'comment' => 'nullable',
                'img_path' => 'nullable' | 'image',
            ]);
    
            if( $request -> hasFile('img_path')){
                $fileName = $request -> img_path -> getClientOriginalName();
                $filePath = $request -> img_path -> storeAs('products', $fileName, 'public');
                $product -> img_path = '/storage/' . $filePath;
            }
    
            if($product -> update($validatedDate)){
                return redirect() -> route('products.show', $product -> id)
                    ->with('success', 'データを更新しました。');
            } else {
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
        $product -> delete();

        return redirect('/products');
    }
}
