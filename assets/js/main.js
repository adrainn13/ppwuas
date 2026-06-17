// Main JavaScript untuk Website Katalog Pestisida

// Global Variables
let currentPage = 1;
let searchKeyword = '';
let selectedCategory = '';
let productsPerPage = 10;

// Document Ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    initializeApp();
    
    // Event Listeners
    attachEventListeners();
});

// Initialize App
function initializeApp() {
    // Load products jika ada container
    if (document.getElementById('productContainer')) {
        loadProducts();
    }
    
    // Setup search
    setupSearch();
    
    // Setup filter chips
    setupFilterChips();
    
    // Setup form validation
    setupFormValidation();
}

// Attach Event Listeners
function attachEventListeners() {
    // Search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function(e) {
            searchKeyword = e.target.value;
            currentPage = 1;
            loadProducts();
        }, 500));
    }
    
    // Filter chips
    const filterChips = document.querySelectorAll('.chip');
    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            // Remove active from all chips
            filterChips.forEach(c => c.classList.remove('active'));
            
            // Add active to clicked chip
            this.classList.add('active');
            
            // Get category
            selectedCategory = this.dataset.category;
            currentPage = 1;
            loadProducts();
        });
    });
    
    // Delete buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete') || e.target.closest('.btn-delete')) {
            e.preventDefault();
            const btn = e.target.classList.contains('btn-delete') ? e.target : e.target.closest('.btn-delete');
            const productId = btn.dataset.id;
            const productName = btn.dataset.name;
            confirmDelete(productId, productName);
        }
    });
}

// Load Products with AJAX
function loadProducts() {
    const container = document.getElementById('productContainer');
    if (!container) return;
    
    // Show loading
    container.innerHTML = '<div class="loading"><div class="spinner"></div></div>';
    
    // Build URL
    const params = new URLSearchParams({
        page: currentPage,
        search: searchKeyword,
        category: selectedCategory,
        per_page: productsPerPage
    });
    
    // Fetch data
    fetch('api/get_products.php?' + params)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderProducts(data.products);
                renderPagination(data.total, data.per_page, data.current_page);
            } else {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h3>Tidak Ada Produk</h3>
                        <p>${data.message || 'Produk tidak ditemukan'}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span>Gagal memuat produk. Silakan coba lagi.</span>
                </div>
            `;
        });
}

// Render Products
function renderProducts(products) {
    const container = document.getElementById('productContainer');
    
    if (products.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>Tidak Ada Produk</h3>
                <p>Produk yang Anda cari tidak ditemukan</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    products.forEach((product, index) => {
        const stockClass = product.stok > 20 ? 'in-stock' : (product.stok > 0 ? 'low-stock' : 'out-of-stock');
        const stockText = product.stok > 0 ? `Stok: ${product.stok}` : 'Habis';
        
        html += `
            <div class="product-card fade-in" style="animation-delay: ${index * 0.1}s" onclick="goToDetail(${product.id_produk})">
                <img src="${product.foto_path || 'assets/images/placeholder.svg'}" 
                     alt="${product.nama_produk}" 
                     class="product-image"
                     onerror="this.src='assets/images/placeholder.svg'">
                <div class="product-badge">
                    <i class="bi bi-${getCategoryIcon(product.nama_kategori)}"></i>
                    ${product.nama_kategori}
                </div>
                <div class="product-body">
                    <div class="product-category">
                        <i class="bi bi-tag"></i>
                        ${product.nama_kondisi}
                    </div>
                    <h3 class="product-title">${product.nama_produk}</h3>
                    <p class="product-brand">${extractBrand(product.deskripsi)}</p>
                    <p class="product-description">${product.kegunaan || product.deskripsi}</p>
                    <div class="product-footer">
                        <div>
                            <div class="product-price">
                                ${formatRupiah(product.harga)}
                                <small>/${product.volume_kemasan}</small>
                            </div>
                        </div>
                        <div class="product-stock ${stockClass}">
                            <i class="bi bi-box"></i>
                            ${stockText}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Render Pagination
function renderPagination(total, perPage, current) {
    const paginationContainer = document.getElementById('paginationContainer');
    if (!paginationContainer) return;
    
    const totalPages = Math.ceil(total / perPage);
    
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    let html = '<ul class="pagination">';
    
    // Previous button
    html += `
        <li class="page-item ${current === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${current - 1}); return false;">
                <i class="bi bi-chevron-left"></i>
            </a>
        </li>
    `;
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= current - 2 && i <= current + 2)) {
            html += `
                <li class="page-item ${i === current ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `;
        } else if (i === current - 3 || i === current + 3) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    // Next button
    html += `
        <li class="page-item ${current === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="changePage(${current + 1}); return false;">
                <i class="bi bi-chevron-right"></i>
            </a>
        </li>
    `;
    
    html += '</ul>';
    paginationContainer.innerHTML = html;
}

// Change Page
function changePage(page) {
    currentPage = page;
    loadProducts();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Go to Detail Page
function goToDetail(productId) {
    window.location.href = `detail.php?id=${productId}`;
}

// Setup Search
function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.setAttribute('placeholder', 'Cari nama produk, merek, atau bahan aktif...');
    }
}

// Setup Filter Chips
function setupFilterChips() {
    // Load categories dynamically if needed
    // This is handled by PHP in the main page
}

// Setup Form Validation
function setupFormValidation() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

// Confirm Delete
function confirmDelete(productId, productName) {
    if (confirm(`Apakah Anda yakin ingin menghapus produk "${productName}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
        deleteProduct(productId);
    }
}

// Delete Product
let isDeleting = false; // Flag to prevent double deletion

function deleteProduct(productId) {
    // Prevent double delete
    if (isDeleting) {
        console.log('Delete already in progress...');
        return;
    }
    
    isDeleting = true;
    
    fetch('../api/delete_product.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + productId
    })
    .then(response => response.json())
    .then(data => {
        isDeleting = false; // Reset flag
        
        if (data.success) {
            alert('Produk berhasil dihapus!');
            // Reload halaman untuk refresh data
            window.location.reload();
        } else {
            alert('Gagal menghapus produk: ' + data.message);
        }
    })
    .catch(error => {
        isDeleting = false; // Reset flag
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus produk');
    });
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function formatRupiah(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

function getCategoryIcon(category) {
    const icons = {
        'Herbisida': 'flower1',
        'Insektisida': 'bug',
        'Fungisida': 'shield-check',
        'Nematisida': 'shield-fill-check',
        'Pupuk NPK': 'droplet-fill',
        'Pupuk Organik': 'tree',
        'ZPT': 'graph-up-arrow',
        'Rodentisida': 'x-octagon',
        'Akarisida': 'eye'
    };
    return icons[category] || 'box';
}

function extractBrand(description) {
    if (!description) return 'Brand';
    const parts = description.split('|');
    return parts[0].trim();
}

// Image Preview for Upload
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Form Validation Messages
function showValidationMessage(input, message) {
    const feedback = input.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = message;
    }
}
