@extends('layouts.app')

@section('title', 'Purchase Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">Purchase Management</h2>
            <p class="text-muted">View and manage all purchases</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Purchase
            </a>
        </div>
    </div>
    
    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-2"></i>Filter Purchases
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.purchases.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            @foreach(['pending', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ request('start_date') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ request('end_date') }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="invoice_number" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" 
                               value="{{ request('invoice_number') }}" placeholder="Search invoice...">
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Purchases Table -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-2"></i>All Purchases
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Admin</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                        <tr>
                            <td>
                                <strong>{{ $purchase->invoice_number }}</strong>
                            </td>
                            <td>{{ $purchase->customer_name }}</td>
                            <td>{{ $purchase->items_count }} items</td>
                            <td>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                            <td>{{ $purchase->admin->name ?? 'N/A' }}</td>
                            <td>
                                @if($purchase->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($purchase->status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.purchases.show', $purchase) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($purchase->status == 'completed')
                                    <form action="{{ route('admin.purchases.cancel', $purchase) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Cancel this purchase? Stock will be restored.');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-2x mb-3"></i>
                                <p>No purchases found</p>
                                <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary btn-sm">
                                    Create Your First Purchase
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto set end date to today if start date is set
    document.getElementById('start_date').addEventListener('change', function() {
        const endDateInput = document.getElementById('end_date');
        if (this.value && !endDateInput.value) {
            endDateInput.value = new Date().toISOString().split('T')[0];
        }
    });
    
    // Confirm before cancel
    function confirmCancel(form) {
        if (confirm('Are you sure you want to cancel this purchase? Stock will be restored.')) {
            form.submit();
        }
        return false;
    }
</script>
@endpush
@endsection