@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">Product Management</h2>
            <p class="text-muted">Manage your furniture products and inventory</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Product
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <i class="fas fa-boxes me-2"></i>Product List
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" id="simpleSearch" class="form-control" placeholder="Search products by name, category...">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach(['Living Room', 'Dining Room', 'Bedroom', 'Home Office', 'Kitchen', 'Lighting', 'Outdoor'] as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Products Table - SIMPLE VERSION -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="simpleProductsTable">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">
                                <a href="#" class="sort-link" data-sort="id">#</a>
                            </th>
                            <th width="10%">Image</th>
                            <th width="25%">
                                <a href="#" class="sort-link" data-sort="name">Product Name</a>
                            </th>
                            <th width="10%">Category</th>
                            <th width="10%">
                                <a href="#" class="sort-link" data-sort="price">Price</a>
                            </th>
                            <th width="10%">
                                <a href="#" class="sort-link" data-sort="stock">Stock</a>
                            </th>
                            <th width="10%">Status</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        @forelse($products as $product)
                        <tr class="product-row" 
                            data-name="{{ strtolower($product->name) }}"
                            data-category="{{ $product->category }}"
                            data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock_quantity }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-couch text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                @if($product->description)
                                <br>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $product->category }}</span>
                            </td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>
                                @php
                                    $quantity = $product->stock_quantity;
                                    $statusClass = $quantity <= 0 ? 'danger' : ($quantity <= 5 ? 'warning' : 'success');
                                    $statusText = $quantity <= 0 ? 'Out of Stock' : ($quantity <= 5 ? 'Low Stock' : 'In Stock');
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.products.stock') }}" 
                                       class="btn btn-outline-warning" title="Manage Stock">
                                        <i class="fas fa-warehouse"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-box-open fa-2x mb-3"></i>
                                <p>No products found. <a href="{{ route('admin.products.create') }}">Add your first product</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} entries
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .sort-link {
        color: inherit;
        text-decoration: none;
    }
    
    .sort-link:hover {
        text-decoration: underline;
    }
    
    .sort-link.active {
        color: #0d6efd;
        font-weight: bold;
    }
    
    .product-row.hidden {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Using SIMPLE table view - DataTables DISABLED');
    
    // Destroy any existing DataTables instances GLOBALLY
    if ($.fn.DataTable) {
        $.fn.DataTable.tables().forEach(function(table) {
            if ($.fn.DataTable.isDataTable(table)) {
                $(table).DataTable().destroy(true);
                console.log('Destroyed DataTable instance');
            }
        });
    }
    
    // Simple search functionality
    $('#simpleSearch').on('keyup', function() {
        var searchTerm = $(this).val().toLowerCase();
        var categoryFilter = $('#categoryFilter').val();
        
        $('.product-row').each(function() {
            var productName = $(this).data('name');
            var productCategory = $(this).data('category');
            var matchesSearch = searchTerm === '' || productName.includes(searchTerm);
            var matchesCategory = categoryFilter === '' || productCategory === categoryFilter;
            
            if (matchesSearch && matchesCategory) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
        
        updateRowNumbers();
    });
    
    // Category filter
    $('#categoryFilter').on('change', function() {
        $('#simpleSearch').trigger('keyup');
    });
    
    // Clear search
    $('#clearSearch').on('click', function() {
        $('#simpleSearch').val('');
        $('#categoryFilter').val('');
        $('.product-row').removeClass('hidden');
        updateRowNumbers();
    });
    
    // Simple sorting
    $('.sort-link').on('click', function(e) {
        e.preventDefault();
        
        var sortType = $(this).data('sort');
        var rows = $('.product-row:not(.hidden)').toArray();
        
        rows.sort(function(a, b) {
            var aVal = $(a).data(sortType);
            var bVal = $(b).data(sortType);
            
            if (sortType === 'name') {
                return aVal.localeCompare(bVal);
            } else {
                return aVal - bVal;
            }
        });
        
        // Reverse if already sorted
        if ($(this).hasClass('active')) {
            rows.reverse();
            $(this).removeClass('active');
        } else {
            $('.sort-link').removeClass('active');
            $(this).addClass('active');
        }
        
        // Reorder rows
        $('#productsTableBody').empty().append(rows);
        updateRowNumbers();
    });
    
    // Update row numbers
    function updateRowNumbers() {
        var visibleRows = $('.product-row:not(.hidden)');
        visibleRows.each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }
    
    // Initialize
    updateRowNumbers();
});
</script>
@endpush