document.addEventListener('DOMContentLoaded', function() {
    const updateMiniCart = () => {
        fetch('index.php?pages=minicart')
            .then(response => response.text())
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Update cart count
                const newCount = tempDiv.querySelector('#CartCount');
                const currentCount = document.querySelector('#CartCount');
                if (newCount && currentCount) {
                    currentCount.textContent = newCount.textContent;
                }
                
                // Update mini cart content
                const newCart = tempDiv.querySelector('#header-cart');
                const currentCart = document.querySelector('#header-cart');
                if (newCart && currentCart) {
                    currentCart.innerHTML = newCart.innerHTML;
                }
            });
    };

    // Listen for cart quantity changes
    document.addEventListener('click', function(e) {
        if (e.target.matches('.qtyBtn')) {
            const form = e.target.closest('form');
            if (form) {
                const formData = new FormData(form);
                
                fetch('index.php?pages=cart&action=update', {
                    method: 'POST',
                    body: formData
                }).then(() => updateMiniCart());
            }
        }
    });

    // Listen for item removals
    document.addEventListener('click', function(e) {
        if (e.target.closest('.cart__remove')) {
            e.preventDefault();
            const link = e.target.closest('.cart__remove');
            const url = link.href;

            if (confirm('Remove this item?')) {
                fetch(url)
                    .then(() => updateMiniCart());
            }
        }
    });
});