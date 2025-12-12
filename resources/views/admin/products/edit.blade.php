@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">Edit Product</h2>
            <p class="text-muted">Update product information</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit me-2"></i>Edit Product Information
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category', $product->category) == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Price (Rp) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Current Image</label>
                                <div>
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                             class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 100px; height: 100px;">
                                            <i class="fas fa-couch text-muted fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Update Product Image (Optional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave empty to keep current image. Max size: 2MB</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Product Details
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Current Stock</small>
                        <h5>{{ $product->stock_quantity }}</h5>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Stock Status</small>
                        <span class="badge {{ $product->stock_status_class == 'danger' ? 'bg-danger' : ($product->stock_status_class == 'warning' ? 'bg-warning' : 'bg-success') }}">
                            {{ $product->stock_status }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Created At</small>
                        <span>{{ $product->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Last Updated</small>
                        <span>{{ $product->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid">
                        <a href="{{ route('admin.products.stock') }}" class="btn btn-outline-warning">
                            <i class="fas fa-warehouse me-2"></i>Manage Stock
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.querySelector('.img-thumbnail') || 
                    document.querySelector('.bg-light');
                
                if (imgElement) {
                    if (imgElement.tagName === 'IMG') {
                        imgElement.src = e.target.result;
                    } else {
                        // Replace placeholder with image
                        const parent = imgElement.parentElement;
                        const newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.className = 'img-thumbnail';
                        newImg.style = 'width: 100px; height: 100px; object-fit: cover;';
                        parent.innerHTML = '';
                        parent.appendChild(newImg);
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection