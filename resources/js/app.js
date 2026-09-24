// WALKEN — Core Vanilla JavaScript

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Drawer
    const menuBtn = document.getElementById('mobileMenuBtn');
    const drawerBackdrop = document.getElementById('drawerBackdrop');
    const drawerCloseBtn = document.getElementById('drawerCloseBtn');
    const mobileDrawer = document.getElementById('mobileDrawer');

    function openDrawer() {
        if (drawerBackdrop && mobileDrawer) {
            drawerBackdrop.classList.add('active');
            mobileDrawer.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDrawer() {
        if (drawerBackdrop && mobileDrawer) {
            drawerBackdrop.classList.remove('active');
            mobileDrawer.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (menuBtn) menuBtn.addEventListener('click', openDrawer);
    if (drawerCloseBtn) drawerCloseBtn.addEventListener('click', closeDrawer);
    if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);

    // 2. Global Toast Auto-dismiss
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(t => {
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transform = 'translateY(10px)';
            t.style.transition = 'all 0.3s ease';
            setTimeout(() => t.remove(), 300);
        }, 4000);
    });

    // 3. Wishlist AJAX Toggle on Product Cards
    document.querySelectorAll('.js-wishlist-toggle').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const productId = btn.dataset.productId;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                // If not logged in, redirect to login
                window.location.href = '/login';
                return;
            }

            try {
                const response = await fetch('/user/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                });

                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();
                if (data.success) {
                    if (data.added) {
                        btn.classList.add('active');
                        btn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="#DC2626" stroke="#DC2626" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>`;
                        showToast(data.message, 'success');
                    } else {
                        btn.classList.remove('active');
                        btn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>`;
                        showToast(data.message, 'info');
                    }

                    // Update header wishlist badge if present
                    const badge = document.getElementById('wishlistCountBadge');
                    if (badge) {
                        badge.textContent = data.wishlist_count;
                        badge.style.display = data.wishlist_count > 0 ? 'flex' : 'none';
                    }
                }
            } catch (err) {
                console.error('Wishlist error:', err);
            }
        });
    });
});

// Toast Helper
function showToast(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
