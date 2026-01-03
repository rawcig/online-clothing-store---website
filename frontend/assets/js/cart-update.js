document.addEventListener('DOMContentLoaded', function() {
    function updateMiniCart() {
        console.log('=== Starting mini cart update ===');
        fetch('index.php?pages=minicart')
            .then(response => {
                console.log('Minicart fetch response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                console.log('Minicart HTML length:', html.length);
                // Validate that we got HTML content
                if (!html || typeof html !== 'string') {
                    throw new Error('Invalid response content');
                }
                
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Update cart count
                const newCount = tempDiv.querySelector('#CartCount');
                const currentCount = document.querySelector('#CartCount');
                console.log('Cart count elements found:', !!newCount, !!currentCount);
                if (newCount && currentCount) {
                    const oldCount = currentCount.textContent;
                    currentCount.textContent = newCount.textContent;
                    console.log('Updated cart count from', oldCount, 'to', newCount.textContent);
                }
                
                // Update mini cart content
                const newCart = tempDiv.querySelector('#header-cart');
                const currentCart = document.querySelector('#header-cart');
                console.log('Cart content elements found:', !!newCart, !!currentCart);
                if (newCart && currentCart) {
                    const oldHTML = currentCart.innerHTML;
                    const newHTML = newCart.innerHTML;
                    console.log('Updating cart content, old length:', oldHTML.length, 'new length:', newHTML.length);
                    try {
                        currentCart.innerHTML = newHTML;
                        console.log('Successfully updated cart content');
                    } catch (e) {
                        console.error('Error updating cart content:', e);
                    }
                }
                
                console.log('=== Finished mini cart update ===');
            })
            .catch(error => {
                console.error('Cart update failed:', error);
                // Don't break the page if cart update fails
            });
    }

    // Listen for manual cart update events
    document.addEventListener('cartUpdated', updateMiniCart);
});