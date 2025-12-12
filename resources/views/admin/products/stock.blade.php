@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">Stock Management</h2>
            <p class="text-muted">Manage product inventory and stock adjustments</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <div id="ajax-alert-container"></div>
    
    <!-- Stock Overview -->
    <div class="row mb-4">
        @forelse($products as $product)
        <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-couch text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1">{{ $product->name }}</h6>
                            <span class="badge-category">{{ $product->category }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Stock Level:</span>
                            <span class="{{ $product->stock_status_class == 'danger' ? 'text-danger' : ($product->stock_status_class == 'warning' ? 'text-warning' : 'text-success') }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php
                                $percentage = min(100, ($product->stock_quantity / 100) * 100);
                                $bgClass = $product->stock_status_class == 'danger' ? 'bg-danger' : 
                                         ($product->stock_status_class == 'warning' ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress-bar {{ $bgClass }}" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    
                    <button class="btn btn-sm btn-outline-primary w-100" 
                            data-bs-toggle="modal" 
                            data-bs-target="#updateStockModal"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-current-stock="{{ $product->stock_quantity }}">
                        <i class="fas fa-edit me-2"></i>Update Stock
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-boxes fa-3x mb-3"></i>
                    <h5>No Products Found</h5>
                    <p>Add some products first to manage stock</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Product
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>
    
    @if($products->count() > 0)
    <div class="row">
        <!-- Low Stock Alert -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert (≤ 5)
                </div>
                <div class="card-body">
                    @forelse($lowStockProducts as $product)
                    <div class="alert alert-warning py-2 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <br>
                            <small>Current Stock: {{ $product->stock_quantity }}</small>
                        </div>
                        <button class="btn btn-sm btn-warning"
                                data-bs-toggle="modal" 
                                data-bs-target="#updateStockModal"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-current-stock="{{ $product->stock_quantity }}">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <p>All products have sufficient stock</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Stock History -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i>Recent Stock History
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Change</th>
                                    <th>Date</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stockHistory as $history)
                                <tr>
                                    <td>{{ $history->product->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $history->change_type == 'restock' ? 'bg-success' : ($history->change_type == 'purchase' ? 'bg-info' : 'bg-warning') }}">
                                            {{ ucfirst($history->change_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($history->change_amount > 0)
                                            <span class="text-success">+{{ $history->change_amount }}</span>
                                        @else
                                            <span class="text-danger">{{ $history->change_amount }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $history->created_at->format('d/m H:i') }}</td>
                                    <td>{{ $history->admin->name ?? 'System' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No stock history yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $stockHistory->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStockForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="modalProductName" readonly>
                        <input type="hidden" id="modalProductId" name="product_id">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Stock</label>
                        <input type="text" class="form-control" id="modalCurrentStock" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="change_type" class="form-label">Update Type *</label>
                        <select class="form-select" id="change_type" name="change_type" required>
                            <option value="restock">Restock (Add to current stock)</option>
                            <option value="manual_adjust">Manual Adjustment (Set exact stock)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity *</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Enter notes about this stock update"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitStockBtn">
                        <span id="submitText">Update Stock</span>
                        <span id="loadingSpinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-category {
        background-color: #e9ecef;
        color: #6c757d;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
    }
    
    .ajax-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Handle modal show
    $('#updateStockModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const productId = button.data('product-id');
        const productName = button.data('product-name');
        const currentStock = button.data('current-stock');
        
        const modal = $(this);
        modal.find('#modalProductId').val(productId);
        modal.find('#modalProductName').val(productName);
        modal.find('#modalCurrentStock').val(currentStock);
        modal.find('#quantity').val('');
        modal.find('#notes').val('');
        
        // Update form action
        const form = modal.find('#updateStockForm');
        form.attr('action', "{{ route('admin.products.updateStock', ':id') }}".replace(':id', productId));
        
        // Reset button state
        $('#submitStockBtn').prop('disabled', false);
        $('#submitText').show();
        $('#loadingSpinner').hide();
    });
    
    // Handle form submission with AJAX
    $('#updateStockForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(form[0]);
        const submitBtn = $('#submitStockBtn');
        const modal = $('#updateStockModal');
        
        // Show loading state
        submitBtn.prop('disabled', true);
        $('#submitText').hide();
        $('#loadingSpinner').show();
        
        // Add CSRF token
        formData.append('_token', '{{ csrf_token() }}');
        
        // Send AJAX request
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showAlert('Stock updated successfully!', 'success');
                    
                    // Close modal after delay
                    setTimeout(function() {
                        modal.modal('hide');
                    }, 1000);
                    
                    // Refresh page after 1.5 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(response.message || 'Failed to update stock', 'danger');
                    resetButtonState();
                }
            },
            error: function(xhr) {
                let errorMessage = 'Failed to update stock. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert(errorMessage, 'danger');
                resetButtonState();
            }
        });
        
        // Reset button state
        function resetButtonState() {
            submitBtn.prop('disabled', false);
            $('#submitText').show();
            $('#loadingSpinner').hide();
        }
    });
    
    // Update quantity placeholder based on type
    $('#change_type').on('change', function() {
        const quantityInput = $('#quantity');
        if ($(this).val() === 'restock') {
            quantityInput.attr('placeholder', 'Enter quantity to add');
            quantityInput.attr('min', '1');
        } else {
            quantityInput.attr('placeholder', 'Enter new total stock');
            quantityInput.attr('min', '0');
        }
    });
    
    // Initialize change type placeholder
    $('#change_type').trigger('change');
    
    // Function to show alert
    function showAlert(message, type = 'info') {
        // Remove existing alerts
        $('.ajax-alert').remove();
        
        // Create alert element
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'danger' ? 'alert-danger' : 
                          type === 'warning' ? 'alert-warning' : 'alert-info';
        
        const iconClass = type === 'success' ? 'fa-check-circle' : 
                         type === 'danger' ? 'fa-exclamation-triangle' : 
                         type === 'warning' ? 'fa-exclamation-circle' : 'fa-info-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show ajax-alert" role="alert">
                <i class="fas ${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $('.ajax-alert').alert('close');
        }, 5000);
    }
    
    // Handle modal hidden event
    $('#updateStockModal').on('hidden.bs.modal', function() {
        // Reset form
        $(this).find('form')[0].reset();
    });
});
</script>
@endpush
@endsection