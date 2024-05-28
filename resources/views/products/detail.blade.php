@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}"
@endsection

@section('content')
<div class="container">
    <h2 class="product__title">商品情報詳細画面</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="product__container">
        <div class="product__row product__row--detail">
            <p>ID</p>
            <p>{{ $product -> id }}</p>
        </div>
        
        <div class="product__row product__row--detail">
            <P>商品画像</P>
            <div class="detail--img">
                <img src="{{ asset($product -> img_path )}}" alt="商品画像">
            </div>
        </div>

        <div class="product__row product__row--detail">
            <p>商品名</p>
            <p>{{ $product -> product_name }}</p>
        </div>

        <div class="product__row product__row--detail">
            <p>メーカー</p>
            <p>{{ $product -> company -> company_name }}</p>
        </div>

        <div class="product__row product__row--detail">
            <p>価格</p>
            <p>¥{{ $product -> price }}</p>
        </div>

        <div class="product__row product__row--detail">
            <p>在庫</p>
            <p>{{ $product -> stock }}</p>
        </div>

        <div class="product__row product__row--detail">
            <p>コメント</p>
            <p>{{ $product -> comment }}</p>
        </div>

        <div class="button__row">
            <a href="{{ route('products.edit', $product) }}" class="btn--orange">編集</a>
            <a href="{{ route('products.index', $product) }}" class="btn--blue">戻る</a>
        </div>
    </div>
</div>
@endsection
