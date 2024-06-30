@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/style.css') }}"
@endsection

@section('content')
<div class="container">
    <h2 class="product__title mb-5">商品一覧画面</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div class="form__row">
        <form class="search__row" action="{{ route('products.search') }}" method="GET">
            <input type="text" name="keyword" id="keyword" placeholder="検索キーワード" value="{{$keyword??''}}">
            <select name="company_id" id="company_id">
                <option selected value="" hidden>メーカー名</option>
                @foreach($companies as $company)
                <option  value="{{ $company -> id }}"@selected( $company -> id == old('company_id,$selectedCompanyId'))>{{ $company -> company_name }}</option>
                @endforeach
            </select>
            <input type="number" name="min_price" id="min_price" placeholder="最小価格" value="{{$minPrice??''}}">
            <input type="number" name="max_price" id="max_price" placeholder="最大価格" value="{{$maxPrice??''}}">
            <input type="number" name="min_stock" id="min_stock" placeholder="最小在庫数" value="{{$minStock??''}}">
            <input type="number" name="max_stock" id="max_stock" placeholder="最大在庫数" value="{{$maxStock??''}}">
            <button type="button" id="search__btn">検索</button>
        </form>
    </div>

    <script>
        
    </script>

    <div class="product__container product__container--index">
        <table class="product__lists" id="sort_table">
            <thead>
                <th>ID
                    <!-- <a href="{{ route('products.search', ['keyword' => $keyword, 'company_id' => $selectedCompanyId, 'sortField' => 'id', 'sortDirection' => $sortField == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        ID
                    </a> -->
                </th>
                <th>商品画像</th>
                <th>商品名
                <!-- <a href="{{ route('products.search', ['keyword' => $keyword, 'company_id' => $selectedCompanyId, 'sortField' => 'product_name', 'sortDirection' => $sortField == 'product_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                    商品名
                </a> -->
                </th>
                <th>価格
                    <!-- <a href="{{ route('products.search', ['keyword' => $keyword, 'company_id' => $selectedCompanyId, 'sortField' => 'price', 'sortDirection' => $sortField == 'price' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        価格
                    </a> -->
                </th>
                <th>在庫数
                    <!-- <a href="{{ route('products.search', ['keyword' => $keyword, 'company_id' => $selectedCompanyId, 'sortField' => 'stock', 'sortDirection' => $sortField == 'stock' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        在庫数
                    </a> -->
                </th>
                <th>メーカー名</th>
                <th colspan="2"><a href="{{ route('products.create', $products) }}" class="btn--orange">新規登録</a></th>
            </thead>
            <tbody>
               
            </tbody>
        </table>
    </div>

    {{ $products -> appends(['keyword' => $keyword, 'selectedCompanyId' => $selectedCompanyId ]) -> links() }}

</div>
@endsection
