data: {
                        'keyword': keyword,
                        'select_company_id': companyId,
                    },

                    let productList = '';
                    

                    $.each(data.products, function(index, product){
                                            productList += `
                                                <tr>
                                                    <td>${product.id}.</td>
                                                    <td><img src="{{ asset( '${product.img_path}' )}}" alt="商品画像"></td>
                                                    <td><img src="{{ asset( ${product.img_path}) }}" alt="商品画像"></td>
                                                    <td>${product.product_name}</td>
                                                    <td>¥${product.price}</td>
                                                    <td>${product.stock}</td>
                                                    <td>${product.company.company_name}</td>
                                                    <td><a href="{{ route('products.show', ${product.id}) }}" class="btn--blue">詳細</a></td>
                                                    <td>
                                                        <form method="POST" action="{{ route('products.destroy', ${product}) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn--red">削除</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            `;
                                        });
                                        $('product__lists tbody').append(productList);
                                    