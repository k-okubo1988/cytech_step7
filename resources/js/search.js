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
