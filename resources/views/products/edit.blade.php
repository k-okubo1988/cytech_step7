@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}"
@endsection

@section('content')
<div class="container">
    <h2 class="product__title">商品情報編集画面</h2>
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="product__container">
        <form method="POST" action="{{ route('products.update', $product) }}" class="product__edit" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="product__row product__row--edit">
                <p class="form-label">ID</p>
                <p class="form-control">{{ $product -> id }}</p>
            </div>

            <div class="product__row product__row--edit">
                <label for="product_name" class="form-label">商品名<span class="require text-danger">*</span></label>
                <input type="text" id="product_name" name="product_name" class="form-control" value="{{ $product -> product_name }}" required>
                @if($errors -> has('product_name'))
                    <div class="alert alert-danger">{{ $errors -> first('product_name') }}</div>
                @endif
            </div>

            <div class="product__row product__row--edit">
                <label for="company_id" class="form-label">メーカー名<span class="require text-danger">*</span></label>
                <select id="company_id" name="company_id" class="form-select" required>
                    @foreach($companies as $company)
                    <option value="{{ $company -> id }}"{{ $product -> company_id == $company -> id ? 'selected' : ''}}>{{ $company -> company_name }}</option>
                    @endforeach
                </select>
                @if($errors -> has('company_id'))
                    <div class="alert alert-danger">{{ $errors -> first('company_id') }}</div>
                @endif
            </div>

            <div class="product__row product__row--edit">
                <label for="price" class="form-label">価格<span class="require text-danger">*</span></label>
                <input type="number" id="price" name="price" class="form-control" value="{{ $product -> price }}" required>
                @if($errors -> has('price'))
                    <div class="alert alert-danger">{{ $errors -> first('price') }}</div>
                @endif
            </div>

            <div class="product__row product__row--edit">
                <label for="stock" class="form-label">在庫数<span class="require text-danger">*</span></label>
                <input type="number" id="stock" name="stock" class="form-control" value="{{ $product -> stock }}" required>
                @if($errors -> has('stock'))
                    <div class="alert alert-danger">{{ $errors -> first('stock') }}</div>
                @endif
            </div>

            <div class="product__row product__row--edit">
                <label for="comment" class="form-label">コメント</label>
                <textarea type="text" id="comment" name="comment" class="form-control">{{ $product -> comment }}</textarea>
            </div>

            <div class="product__row product__row--edit">
                <label for="img_path" class="form-label">商品画像</label>
                <input type="file" id="img_path" name="img_path" class="form-control">
                <img src="{{ asset($product -> imag_path )}}" alt="商品画像">
            </div>
        
            <div class="button__row">
                <button type="submit" class="btn--orange">更新</button>
                <a href="{{ route('products.index') }}" class="btn--blue">戻る</a>
            </div>
        </form>
    </div>
</div>
@endsection
