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
        $("#search__btn").click(function(event){
    event.preventDefault();

    let keyword = $('#keyword').val();
    let selectedCompanyId = $('#company_id').find('option:selected').val();
    let minPrice = $('#min_price').val();
    let maxPrice = $('#max_price').val();
    let minStock = $('#min_stock').val();
    let maxStock = $('#max_stock').val();

    // console.log(keyword);
    // console.log(companyId);

    $('.product__lists tbody').empty();


    $.ajax({
        type: 'GET',
        url:"{{ route('products.search') }}",
        data: {
            'keyword': keyword,
            'company_id': selectedCompanyId,
            'min_price': minPrice,
            'max_price': maxPrice,
            'min_stock': minStock,
            'max_stock': maxStock,
        },
        dataType: 'json',
    })
    .done(function(data){
        console.log(data);
        let html;

        $.each(data.products,function(index, product){
            html += `
                <tr>
                    <td>${ product.id }.</td>
                    <td><img src="${data.products[index].img_path}" alt="商品画像"></td>
                    <td>${ product.product_name}</td>
                    <td>¥${ product.price }</td>
                    <td>${ product.stock }</td>
                    <td value="product.company_id">${ product.company.company_name }</td>
                    
                </tr>
            `;
        });
        
        $(".product__lists tbody").append(html);
    })
    .fail(function(xhr, status, error){
        console.error(error);
    });
    
});

    </script>

    <div class="product__container product__container--index">
        <table class="product__lists">
            <thead>
                <th>@sortablelink('id', 'ID')</th>
                <th>商品画像</th>
                <th>@sortablelink('product_name', '商品名')</th>
                <th>@sortablelink('price', '価格')</th>
                <th>@sortablelink('stock', '在庫数')</th>
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
                    <td>{{ $product -> company -> company_name }}</td>
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

    {{ $products -> appends(['keyword' => $keyword, 'selectedCompanyId' => $selectedCompanyId ]) -> links() }}

</div>
@endsection
