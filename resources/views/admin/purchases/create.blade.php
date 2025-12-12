@extends('layouts.app')

@section('title', 'Create New Purchase')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">Create New Purchase</h2>
            <p class="text-muted">Process a new furniture purchase</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Purchases
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus-circle me-2"></i>Purchase Details
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchaseForm">
                        @csrf
                        
                        <!-- Customer Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" 
                                       value="{{ old('customer_name') }}" placeholder="Enter customer name">
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Customer Email</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                       id="customer_email" name="customer_email" 
                                       value="{{ old('customer_email') }}" placeholder="Enter customer email">
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Product Selection -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Products</h5>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addProductRow()">
                                    <i class="fas fa-plus me-1"></i>Add Product
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th width="40%">Product</th>
                                            <th width="25%">Price</th>
                                            <th width="20%">Quantity</th>
                                            <th width="10%">Subtotal</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="productsTableBody">
                                        <!-- Product rows will be added here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                            <td>
                                                <input type="hidden" name="total_amount" id="totalAmount" value="0">
                                                <span class="fw-bold" id="totalAmountDisplay">Rp 0</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Process Purchase
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Available Products -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-boxes me-2"></i>Available Products
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @foreach($products as $product)
                    <div class="product-item mb-2 p-2 border rounded" 
                         data-product-id="{{ $product->id }}"
                         data-product-name="{{ $product->name }}"
                         data-product-price="{{ $product->price }}"
                         data-product-stock="{{ $product->stock_quantity }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <small class="text-muted">Stock: {{ $product->stock_quantity }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-1" 
                                        onclick="addProduct({{ $product->id }})">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Purchase Summary -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Quick Tips
                </div>
                <div class="card-body">
                    <ul class="small text-muted mb-0">
                        <li>Select products from available list</li>
                        <li>Stock will be automatically deducted</li>
                        <li>Invoice number will be generated automatically</li>
                        <li>Purchase cannot be edited after creation</li>
                        <li>Cancel purchases to restore stock</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .product-item:hover {
        background-color: #f8f9fa;
    }
    
    .product-row {
        transition: all 0.3s;
    }
</style>
@endpush

@push('scripts')
<script>
    let productCounter = 0;
    let addedProducts = new Set();
    
    // Available products data
    const productsData = {
        @foreach($products as $product)
        {{ $product->id }}: {
            name: "{{ $product->name }}",
            price: {{ $product->price }},
            stock: {{ $product->stock_quantity }}
        },
        @endforeach
    };
    
    // Add product from available list
    function addProduct(productId) {
        if (addedProducts.has(productId)) {
            alert('This product is already added. Update quantity instead.');
            return;
        }
        
        const product = productsData[productId];
        if (!product) return;
        
        const rowId = `product_${productCounter++}`;
        const row = `
            <tr id="${rowId}" class="product-row">
                <td>
                    <input type="hidden" name="items[${rowId}][product_id]" value="${productId}">
                    ${product.name}
                </td>
                <td>
                    <input type="hidden" class="price-input" value="${product.price}">
                    Rp ${formatNumber(product.price)}
                </td>
                <td>
                    <input type="number" 
                           class="form-control form-control-sm quantity-input" 
                           name="items[${rowId}][quantity]" 
                           value="1" 
                           min="1" 
                           max="${product.stock}"
                           onchange="updateSubtotal('${rowId}')"
                           required>
                </td>
                <td>
                    <input type="hidden" class="subtotal-input" value="${product.price}">
                    <span class="subtotal-display">Rp ${formatNumber(product.price)}</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProduct('${rowId}', ${productId})">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#productsTableBody').append(row);
        addedProducts.add(productId);
        updateTotal();
    }
    
    // Add empty product row
    function addProductRow() {
        const rowId = `product_${productCounter++}`;
        const row = `
            <tr id="${rowId}" class="product-row">
                <td>
                    <select class="form-select form-select-sm product-select" 
                            name="items[${rowId}][product_id]" 
                            onchange="onProductSelectChange('${rowId}', this.value)"
                            required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock_quantity }}">
                            {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                        </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="hidden" class="price-input">
                    <span class="price-display text-muted">-</span>
                </td>
                <td>
                    <input type="number" 
                           class="form-control form-control-sm quantity-input" 
                           name="items[${rowId}][quantity]" 
                           value="1" 
                           min="1" 
                           onchange="updateSubtotal('${rowId}')"
                           disabled
                           required>
                </td>
                <td>
                    <input type="hidden" class="subtotal-input">
                    <span class="subtotal-display text-muted">-</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProduct('${rowId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#productsTableBody').append(row);
        updateTotal();
    }
    
    // Handle product selection change
    function onProductSelectChange(rowId, productId) {
        const row = $(`#${rowId}`);
        const product = productsData[productId];
        
        if (product) {
            row.find('.price-input').val(product.price);
            row.find('.price-display').text(`Rp ${formatNumber(product.price)}`).removeClass('text-muted');
            
            const quantityInput = row.find('.quantity-input');
            quantityInput.attr('max', product.stock);
            quantityInput.prop('disabled', false);
            
            updateSubtotal(rowId);
            
            // Mark as added
            if (addedProducts.has(parseInt(productId))) {
                alert('This product is already added in another row.');
                row.find('.product-select').val('');
                row.find('.price-display').text('-').addClass('text-muted');
                row.find('.subtotal-display').text('-').addClass('text-muted');
                quantityInput.val(1).prop('disabled', true);
                return;
            }
            addedProducts.add(parseInt(productId));
        }
    }
    
    // Update subtotal for a row
    function updateSubtotal(rowId) {
        const row = $(`#${rowId}`);
        const price = parseFloat(row.find('.price-input').val()) || 0;
        const quantity = parseInt(row.find('.quantity-input').val()) || 0;
        const subtotal = price * quantity;
        
        row.find('.subtotal-input').val(subtotal);
        row.find('.subtotal-display').text(`Rp ${formatNumber(subtotal)}`).removeClass('text-muted');
        
        updateTotal();
    }
    
    // Update total amount
    function updateTotal() {
        let total = 0;
        $('.subtotal-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        
        $('#totalAmount').val(total);
        $('#totalAmountDisplay').text(`Rp ${formatNumber(total)}`);
    }
    
    // Remove product row
    function removeProduct(rowId, productId = null) {
        $(`#${rowId}`).remove();
        if (productId) {
            addedProducts.delete(productId);
        }
        updateTotal();
    }
    
    // Format number with thousand separators
    function formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Form validation
    document.getElementById('purchaseForm').addEventListener('submit', function(e) {
        const productRows = $('#productsTableBody tr').length;
        if (productRows === 0) {
            e.preventDefault();
            alert('Please add at least one product to the purchase.');
            return;
        }
        
        // Validate quantity doesn't exceed stock
        let isValid = true;
        $('.quantity-input').each(function() {
            const maxStock = parseInt($(this).attr('max')) || 0;
            const quantity = parseInt($(this).val()) || 0;
            
            if (quantity > maxStock) {
                isValid = false;
                alert(`Quantity exceeds available stock for one of the products. Max stock: ${maxStock}`);
                return false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Initialize with one empty row
    $(document).ready(function() {
        addProductRow();
    });
</script>
@endpush
@endsection