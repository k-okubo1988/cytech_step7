@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}"
@endsection

@section('content')
<div class="container">
    <h2 class="product__title">商品新規登録画面</h2>
    
    <div class="product__container">
        <form method="POST" action="{{ route('products.store') }}" class="product__regist" enctype="multipart/form-data">
            @csrf
            <div class="product__row product__row--regist">
                <label for="product_name" class="form-label">商品名<span class="require text-danger">*</span></label>
                <input type="text" id="product_name" name="product_name" class="form-control" required>
                @if($errors -> has('product_name'))
                    <div class="alert alert-danger">{{ $errors -> first('product_name') }}</div>
                @endif
            </div>

            <div class="product__row product__row--regist">
                <label for="company_id"  class="form-label">メーカー名<span class="require text-danger">*</span></label>
                <select id="company_id" name="company_id" class="form-select">
                    <option selected>メーカー名</option>
                    @foreach($companies as $company)
                    <option value="{{ $company -> id }}">{{ $company -> company_name }}</option>
                    @endforeach
                </select>
                @if($errors -> has('company_id'))
                    <div class="alert alert-danger">{{ $errors -> first('company_id') }}</div>
                @endif
            </div>

            <div class="product__row product__row--regist">
                <label for="price"  class="form-label">価格<span class="require text-danger">*</span></label>
                <input type="number" id="price" name="price" class="form-control" required>
                @if($errors -> has('price'))
                    <div class="alert alert-danger">{{ $errors -> first('price') }}</div>
                @endif
            </div>

            <div class="product__row product__row--regist">
                <label for="stock" class="form-label">在庫数<span class="require text-danger">*</span></label>
                <input type="number" id="stock" name="stock" class="form-control" required>
                @if($errors -> has('stock'))
                    <div class="alert alert-danger">{{ $errors -> first('stock') }}</div>
                @endif
            </div>

            <div class="product__row product__row--regist">
                <label for="comment" class="form-label">コメント</label>
                <textarea type="text" id="comment" name="comment" class="form-control"></textarea>
            </div>

            <div class="product__row product__row--regist">
                <label for="img_path" class="form-label">商品画像</label>
                <input type="file" name="img_path" id="img_path" class="form-btn">
            </div>
        
            <div class="button__row">
                <button type="submit" class="btn--orange">新規登録</button>
                <div class="btn--blue">
                    <a href="{{ route('products.index') }}">戻る</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
