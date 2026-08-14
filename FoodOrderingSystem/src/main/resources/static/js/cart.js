// Cart state
let cart = [];

function addToCart(id, name, price) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push({ id, name, price: parseFloat(price), quantity: 1 });
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const totalEl = document.getElementById('cartTotal');
    if (!container) return;

    container.innerHTML = cart.map(item => `
        <div class="cart-item">
            <span>${item.quantity}x ${item.name}</span>
            <span>${(item.price * item.quantity).toFixed(2)} €</span>
        </div>
    `).join('');

    const total = cart.reduce((sum, i) => sum + i.price * i.quantity, 0);
    totalEl.textContent = total.toFixed(2) + ' €';
}

async function checkout() {
    if (cart.length === 0) return alert('Votre panier est vide');
    const address = prompt('Adresse de livraison:');
    if (!address) return;

    const token = localStorage.getItem('jwt_token');
    if (!token) return (window.location.href = '/login');

    try {
        const res = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                deliveryAddress: address,
                items: cart.map(i => ({ menuItemId: i.id, quantity: i.quantity }))
            })
        });
        if (res.ok) {
            cart = [];
            renderCart();
            alert('Commande passée avec succès ! 🎉');
            window.location.href = '/orders/my';
        } else {
            const err = await res.json();
            alert(err.error || 'Erreur lors de la commande');
        }
    } catch (e) {
        alert('Erreur réseau');
    }
}

// Bind events
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', () =>
            addToCart(btn.dataset.id, btn.dataset.name, btn.dataset.price));
    });

    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) checkoutBtn.addEventListener('click', checkout);
});
