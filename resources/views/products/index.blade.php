@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}"
@endsection

@section('content')
<div class="container">
    <h2 class="product__title mb-5">商品一覧画面</h2>
    <div class="form__row">
        <form class="search__row" action="{{ route('products.index') }}" method="GET">
            <input type="text" name="keyword" placeholder="検索キーワード" value="{{$keyword??''}}">
            <select name="company_id" id="company_id">
                <option selected>メーカー名</option>
                @foreach($companies as $company)
                <option value="{{ $company -> id }}"@selected( $company -> id == old('company_id,$selectedCompanyId'))>{{ $company -> company_name }}</option>
                @endforeach
            </select>
            <button>検索</button>
        </form>
    </div>
    <div class="product__container product__container--index">
        <table class="product__lists">
            <thead>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th colspan="2"><a href="{{ route('products.create', $products) }}" class="btn--orange">新規登録</a></th>
            </thead>
            <tbody>
                <!-- 繰り返し処理 -->
                @foreach($products as $product)
                <tr>
                    <td>{{ $product -> id }}.</td>
                    <td><img src="{{ asset( $product -> img_path) }}" alt="商品画像"></td>
                    <td>{{ $product -> product_name}}</td>
                    <td>¥{{ $product -> price }}</td>
                    <td>{{ $product -> stock }}</td>
                    <td>{{ $product -> company_name }}</td>
                    <td><a href="{{ route('products.show', $product) }}" class="btn--blue">詳細</a></td>
                    <td>
                        <form method="POST" action="{{ route('products.destroy', $product) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn--red">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $products -> appends(['keyword' => $keyword ]) -> links() }}
</div>
@endsection
