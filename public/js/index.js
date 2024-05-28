document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn--red').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            if(confirm('この商品を削除してよろしいですか？')) {
                e.target.closest('form').submit();
            }
        });
    });
});
