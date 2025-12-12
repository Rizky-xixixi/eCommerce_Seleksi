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
                <button class="btn btn-primary" onclick="printInvoice()">
                    <i class="fas fa-print me-2"></i>Print Invoice
                </button>
            </div>
        </div>
    </div>
    
    <!-- Invoice Container untuk Print -->
    <div class="row">
        <div class="col-12">
            <div class="card invoice-container">
                <div class="card-body p-4">
                    <!-- Invoice Header -->
                    <div class="row mb-4 border-bottom pb-4">
                        <div class="col-6">
                            <h3 class="mb-3 text-primary">FURNITURE STORE</h3>
                            <address class="mb-0">
                                <strong>Store Address:</strong><br>
                                123 Furniture Street<br>
                                Jakarta, Indonesia 10110<br>
                                Phone: (021) 1234-5678<br>
                                Email: info@furniturestore.com
                            </address>
                        </div>
                        <div class="col-6 text-end">
                            <h2 class="mb-3 text-uppercase">INVOICE</h2>
                            <p class="mb-1"><strong>Invoice #:</strong> {{ $purchase->invoice_number }}</p>
                            <p class="mb-1"><strong>Date:</strong> {{ $purchase->purchase_date->format('d F Y') }}</p>
                            <p class="mb-1"><strong>Time:</strong> {{ $purchase->purchase_date->format('H:i') }}</p>
                            <p class="mb-0">
                                <strong>Status:</strong> 
                                <span class="badge {{ $purchase->status == 'completed' ? 'bg-success' : ($purchase->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Customer & Admin Info -->
                    <div class="row mb-4 border-bottom pb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">CUSTOMER INFORMATION</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="120"><strong>Name:</strong></td>
                                    <td>{{ $purchase->customer_name }}</td>
                                </tr>
                                @if($purchase->customer_email)
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $purchase->customer_email }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">PROCESSED BY</h5>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="120"><strong>Admin:</strong></td>
                                    <td>{{ $purchase->admin->name ?? 'System' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date:</strong></td>
                                    <td>{{ $purchase->purchase_date->format('d F Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Items Table -->
                    <div class="mb-4">
                        <h5 class="mb-3">PURCHASE ITEMS</h5>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="45%">Product Description</th>
                                    <th width="15%" class="text-end">Unit Price</th>
                                    <th width="10%" class="text-center">Qty</th>
                                    <th width="15%" class="text-end">Subtotal</th>
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
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>TOTAL AMOUNT:</strong></td>
                                    <td class="text-end">
                                        <h5 class="mb-0">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</h5>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Footer Notes -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="border-top pt-3">
                                @if($purchase->status == 'cancelled')
                                <div class="alert alert-danger mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>CANCELLED INVOICE</strong> - This purchase was cancelled on {{ $purchase->cancelled_at->format('d F Y, H:i') }}. Stock has been restored to inventory.
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="mb-2">Payment Terms:</h6>
                                        <p class="small mb-0">Payment due upon receipt. All prices include tax.</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <h6 class="mb-2">Thank you for your business!</h6>
                                        <p class="small mb-0">For inquiries, please contact our customer service.</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Print Only Information -->
                    <div class="row mt-4 print-only">
                        <div class="col-12">
                            <div class="text-center border-top pt-3">
                                <small class="text-muted">
                                    <strong>Furniture Store</strong> | 123 Furniture Street, Jakarta | Phone: (021) 1234-5678 | Email: info@furniturestore.com<br>
                                    Invoice: {{ $purchase->invoice_number }} | Generated: {{ now()->format('d F Y, H:i:s') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions Sidebar (Non-Print) -->
    <div class="row mt-4 no-print">
        <div class="col-lg-4">
            <div class="card">
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
                        <button class="btn btn-outline-primary" onclick="printInvoice()">
                            <i class="fas fa-print me-2"></i>Print Invoice
                        </button>
                        <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>View All Purchases
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Purchase Summary -->
            <div class="card mt-3">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Purchase Summary
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Invoice:</strong></td>
                            <td class="text-end">{{ $purchase->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td class="text-end">{{ $purchase->purchase_date->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Customer:</strong></td>
                            <td class="text-end">{{ $purchase->customer_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Items:</strong></td>
                            <td class="text-end">{{ $purchase->items->count() }} items</td>
                        </tr>
                        <tr>
                            <td><strong>Amount:</strong></td>
                            <td class="text-end"><h5 class="mb-0">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</h5></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td class="text-end">
                                <span class="badge {{ $purchase->status == 'completed' ? 'bg-success' : ($purchase->status == 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Invoice Container Styling */
    .invoice-container {
        background: white;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Print Styles */
    @media print {
        /* Hide everything except invoice */
        body * {
            visibility: hidden;
            margin: 0;
            padding: 0;
        }
        
        /* Show only invoice container */
        .invoice-container,
        .invoice-container * {
            visibility: visible;
        }
        
        /* Position and size the invoice */
        .invoice-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
            border: none;
            box-shadow: none;
            background: white;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-body {
            padding: 20px !important;
        }
        
        /* Hide non-print elements */
        .no-print,
        .no-print *,
        .btn,
        .btn-group,
        .sidebar,
        .top-navbar,
        .navbar,
        footer,
        .row.mb-4,
        .col-lg-4,
        .breadcrumb,
        .alert-danger {
            display: none !important;
        }
        
        /* Show print-only elements */
        .print-only {
            display: block !important;
        }
        
        /* Adjust table for print */
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        
        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        
        /* Ensure proper text color */
        * {
            color: #000 !important;
            background: transparent !important;
        }
        
        /* Remove background colors */
        .badge {
            border: 1px solid #000 !important;
            background: transparent !important;
            color: #000 !important;
        }
        
        /* Remove borders from some elements */
        .border-bottom,
        .border-top {
            border-color: #000 !important;
        }
        
        /* Force page break after invoice */
        .invoice-container {
            page-break-after: always;
        }
    }
    
    /* Hide print-only elements on screen */
    .print-only {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    function printInvoice() {
        // Open print dialog
        window.print();
    }
    
    // Auto-trigger print dialog if requested
    @if(request()->has('print'))
        $(document).ready(function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    @endif
</script>
@endpush
@endsection