$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


document.addEventListener('DOMContentLoaded', () => {
    const productListsBody = document.querySelector('.product__lists tbody');
    
    $("#search__btn").click(function(event){
        event.preventDefault();
        $(document).ready(function(){
            $('#sort_table').tablesorter({
                headers: {
                    0: { sorter: 'digit' },
                    3: { sorter: 'digit' },
                    4: { sorter: 'digit' },
                }
            });
        });
        
        let keyword = $('#keyword').val();
        let selectedCompanyId = $('#company_id').find('option:selected').val();
        let minPrice = $('#min_price').val();
        let maxPrice = $('#max_price').val();
        let minStock = $('#min_stock').val();
        let maxStock = $('#max_stock').val();
        
        // console.log(keyword);
        // console.log(selectedCompanyId);
        
        $('.product__lists tbody').empty();
        
        
        $.ajax({
            type: 'GET',
            url:"products/search",
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
            let html = '';
            
            $.each(data.products,function(index, product){
                html += `
                <tr data-product-id="${product.id}">
                <td>${ product.id }</td>
                <td><img src="${data.products[index].img_path}" alt="商品画像"></td>
                <td>${ product.product_name}</td>
                <td>¥${ product.price }</td>
                <td>${ product.stock }</td>
                <td value="${product.company.id}">${ product.company.company_name }</td>
                <td><a href="/cytech_step7/public/products/${ product.id }" class="btn--blue">詳細</a></td>
                <td><span class="btn--red">削除</span></td>
                </tr>
                `;
            });
            
            // $(".product__lists tbody").append(html);
            productListsBody.innerHTML += html;

            $('#sort_table').trigger('update').trigger('sorton',[['0','asc']]);

            let deleteButtons = productListsBody.querySelectorAll('.btn--red');
            deleteButtons.forEach(function(button){
                button.addEventListener('click', handleDeleteClick);
            });
        })
        .fail(function(xhr, status, error){
            console.error(error);
        });
    });
});

function handleDeleteClick(event){
    event.preventDefault();

    if(confirm('この商品を削除してよろしいですか？')) {
        // 削除対象の製品 ID を取得
        let $row = event.currentTarget.closest('tr');
        let productId = $row.dataset.productId;
        
        
        // Ajax 通信で削除処理を行う
        $.ajax({
            url:"products/" + productId,
            type: 'POST',
            data: {
                "_method": "DELETE",
                "_token": $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(response) {
            // 削除成功時の処理
            console.log('削除成功:', response);
            // 削除した行を表から削除する
            $row.remove();
        })
        .fail(function(xhr, status, error) {
            // 削除失敗時の処理
            console.error('削除失敗:', error);
        });
    }
}


