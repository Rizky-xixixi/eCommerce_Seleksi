@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">Dashboard Overview</h2>
            <p class="text-muted">
                @auth('admin')
                    Welcome back, {{ Auth::guard('admin')->user()->name }}!
                @else
                    Welcome to Furniture Admin System!
                @endauth
                Here's your furniture store summary.
            </p>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" onclick="toggleChatbot()">
                <i class="fas fa-robot me-2"></i>AI Assistant
            </button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card furniture-bg">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-muted mb-0">Total Products</h5>
                            <h2 class="mb-0">{{ $totalProducts }}</h2>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-couch fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card furniture-bg">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-muted mb-0">Total Purchases</h5>
                            <h2 class="mb-0">{{ $totalPurchases }}</h2>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-shopping-cart fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card furniture-bg">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-muted mb-0">Total Revenue</h5>
                            <h2 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card furniture-bg">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title text-muted mb-0">Low Stock Items</h5>
                            <h2 class="mb-0">{{ $lowStockProducts }}</h2>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daily & Monthly Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-day me-2"></i>Today's Activity
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3>{{ $todayPurchases }}</h3>
                            <p class="text-muted mb-0">Purchases</p>
                        </div>
                        <div class="col-6">
                            <h3>Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                            <p class="text-muted mb-0">Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-2"></i>This Month
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3>{{ $monthlyPurchases }}</h3>
                            <p class="text-muted mb-0">Purchases</p>
                        </div>
                        <div class="col-6">
                            <h3>Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
                            <p class="text-muted mb-0">Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Recent Purchases -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-history me-2"></i>Recent Purchases
                    </div>
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPurchases as $purchase)
                                <tr>
                                    <td>
                                        <a href="#" class="text-decoration-none">{{ $purchase->invoice_number }}</a>
                                    </td>
                                    <td>{{ $purchase->customer_name }}</td>
                                    <td>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                    <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                                    <td>{!! $purchase->status_badge !!}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No purchases yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Low Stock Alert -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-exclamation-circle me-2"></i>Low Stock Alert
                </div>
                <div class="card-body">
                    @forelse($lowStockItems as $product)
                    <div class="alert alert-warning py-2 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <br>
                            <small>Stock: {{ $product->stock_quantity }}</small>
                        </div>
                        <a href="{{ route('admin.products.stock') }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-check-circle fa-2x mb-2"></i>
                        <p>All products have sufficient stock</p>
                    </div>
                    @endforelse
                    
                    @if($lowStockItems->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.products.stock') }}" class="btn btn-sm btn-outline-warning">
                            Manage All Stock
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chatbot Window (Hidden by default) -->
<div id="chatbotWindow" class="chatbot-window d-none">
    <div class="chatbot-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="fas fa-robot me-2"></i>AI Assistant</h5>
            <small class="opacity-75">Furniture Store Helper</small>
        </div>
        <button class="btn btn-sm btn-light" onclick="toggleChatbot()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="chatbot-messages" id="chatMessages">
        <div class="message bot-message">
            Hello! I'm your Furniture Store Assistant. How can I help you today?
            <div class="text-muted small mt-1">{{ now()->format('H:i') }}</div>
        </div>
    </div>
    
    <div class="chatbot-input">
        <div class="input-group">
            <input type="text" class="form-control" id="chatInput" placeholder="Ask me anything..." onkeypress="handleKeyPress(event)">
            <button class="btn btn-primary" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let chatbotVisible = false;
    
    function toggleChatbot() {
        const chatbot = document.getElementById('chatbotWindow');
        if (chatbotVisible) {
            chatbot.classList.add('d-none');
        } else {
            chatbot.classList.remove('d-none');
            document.getElementById('chatInput').focus();
        }
        chatbotVisible = !chatbotVisible;
    }
    
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }
    
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        input.value = '';
        
        // Send to server
        fetch("{{ route('admin.chatbot.chat') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Add bot response
            addMessage(data.response, 'bot', data.timestamp);
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('Sorry, I encountered an error. Please try again.', 'bot');
        });
    }
    
    function addMessage(text, sender, timestamp = null) {
        const messagesDiv = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        messageDiv.innerHTML = `
            ${text}
            <div class="text-muted small mt-1">${timestamp || new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
        `;
        messagesDiv.appendChild(messageDiv);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }
</script>
@endpush
@endsection