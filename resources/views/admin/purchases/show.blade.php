@extends('layouts.app')

@section('title', 'Purchase Invoice')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">Purchase Invoice</h2>
            <p class="text-muted">Invoice #{{ $purchase->invoice_number }}</p>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Purchases
                </a>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print Invoice
                </button>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-receipt me-2"></i>Invoice Details
                </div>
                <div class="card-body">
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Furniture Store</h5>
                            <address class="mb-0">
                                123 Furniture Street<br>
                                Jakarta, Indonesia 10110<br>
                                Phone: (021) 1234-5678<br>
                                Email: info@furniturestore.com
                            </address>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="mb-3">INVOICE</h5>
                            <p class="mb-1"><strong>Invoice #:</strong> {{ $purchase->invoice_number }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $purchase->purchase_date->format('d M Y') }}</p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge {{ $purchase->status == 'completed' ? 'bg-success' : ($purchase->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Customer Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Bill To:</h6>
                            <p class="mb-0">
                                <strong>{{ $purchase->customer_name }}</strong><br>
                                @if($purchase->customer_email)
                                {{ $purchase->customer_email }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">Processed By:</h6>
                            <p class="mb-0">{{ $purchase->admin->name ?? 'System' }}</p>
                        </div>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price_at_purchase, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Notes -->
                    @if($purchase->status == 'cancelled')
                    <div class="alert alert-danger">
                        <i class="fas fa-info-circle me-2"></i>
                        This purchase was cancelled on {{ $purchase->cancelled_at->format('d M Y, H:i') }}.
                        {{-- Hapus baris cancelled_by jika tidak ada --}}
                        {{-- Cancelled by: {{ $purchase->cancelledBy->name ?? 'System' }} --}}
                        Stock has been restored to inventory.
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cogs me-2"></i>Actions
                </div>
                <div class="card-body">
                    @if($purchase->status == 'completed')
                    <form action="{{ route('admin.purchases.cancel', $purchase) }}" method="POST" 
                          onsubmit="return confirm('Cancel this purchase? Stock will be restored.');">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100 mb-3">
                            <i class="fas fa-times me-2"></i>Cancel Purchase
                        </button>
                    </form>
                    @endif
                    
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Invoice
                        </a>
                        <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>View All Purchases
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Purchase Summary -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Purchase Summary
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Invoice Number</small>
                        <strong>{{ $purchase->invoice_number }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Purchase Date</small>
                        <strong>{{ $purchase->purchase_date->format('d M Y, H:i') }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Customer Name</small>
                        <strong>{{ $purchase->customer_name }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Items Count</small>
                        <strong>{{ $purchase->items->count() }} items</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Amount</small>
                        <h5 class="mb-0">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</h5>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Status</small>
                        <span class="badge {{ $purchase->status == 'completed' ? 'bg-success' : ($purchase->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .sidebar, .top-navbar, .btn-group, .card-header, .col-lg-4 .card:not(.print-show) {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }
        
        .content-wrapper {
            padding: 0 !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-body {
            padding: 0 !important;
        }
        
        .container-fluid {
            padding: 20px !important;
        }
        
        .row {
            margin: 0 !important;
        }
        
        .col-lg-8 {
            width: 100% !important;
        }
        
        .col-lg-4 {
            display: none !important;
        }
    }
</style>
@endpush
@endsection