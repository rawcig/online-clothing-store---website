// Validation functions for cart functionality
function validateSelections() {
    const selectedColor = document.querySelector('input[name="option-0"]:checked');
    const selectedSize = document.querySelector('input[name="option-1"]:checked');
    
    if (!selectedColor) {
        alert("Please select a color");
        return false;
    }
    
    if (!selectedSize) {
        alert("Please select a size");
        return false;
    }
    
    return true;
}

// Function to update cart count in header
function updateCartCount(newCount) {
    const cartCountElement = document.querySelector('#CartCount');
    if (cartCountElement) {
        cartCountElement.textContent = newCount;
    }
}

// Function to refresh the mini cart display
function refreshMiniCart() {
    // Trigger cart update event to refresh the mini cart
    document.dispatchEvent(new Event('cartUpdated'));
}

document.addEventListener("DOMContentLoaded", () => {
    console.log("cart.js loaded");
});
