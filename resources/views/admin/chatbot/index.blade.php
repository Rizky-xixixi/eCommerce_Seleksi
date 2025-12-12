@extends('layouts.app')

@section('title', 'AI Chatbot Assistant')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h4 mb-0">🤖 AI Chatbot Assistant</h2>
            <p class="text-muted">Get help with furniture store management</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-info btn-sm" onclick="checkStatus()">
                <i class="fas fa-sync-alt me-1"></i>Check Status
            </button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-robot me-2"></i>Chat with AI Assistant
                    <span class="badge bg-success float-end" id="connectionStatus">🟢 Connected</span>
                </div>
                <div class="card-body p-0">
                    <div class="chat-container" style="height: 500px; display: flex; flex-direction: column;">
                        <!-- Chat Messages -->
                        <div class="chat-messages p-4" id="chatMessages" 
                             style="flex: 1; overflow-y: auto; background-color: #f8f9fa;">
                            <!-- Initial message -->
                            <div class="message bot-message mb-3">
                                <div class="message-content bg-light p-3 rounded">
                                    <strong>🤖 AI Assistant:</strong> 
                                    <p class="mb-1">Halo! Saya asisten AI untuk sistem admin toko furnitur Anda.</p>
                                    <p class="mb-1">Saya dapat membantu dengan:</p>
                                    <ul class="mb-0">
                                        <li>📦 <strong>Manajemen Produk</strong> - Tambah, edit, hapus produk</li>
                                        <li>📊 <strong>Kontrol Stok</strong> - Monitor dan update inventory</li>
                                        <li>🛒 <strong>Proses Pembelian</strong> - Handle customer orders</li>
                                        <li>📈 <strong>Analytics Dashboard</strong> - View sales reports</li>
                                    </ul>
                                    <p class="mt-2 mb-0">Silakan tanyakan apa yang Anda butuhkan! 😊</p>
                                </div>
                                <small class="text-muted ms-3">{{ now()->format('H:i') }}</small>
                            </div>
                        </div>
                        
                        <!-- Chat Input -->
                        <div class="chat-input p-3 border-top">
                            <form id="chatForm" class="d-flex gap-2">
                                @csrf
                                <input type="text" class="form-control" id="messageInput" 
                                       placeholder="Ketik pesan Anda di sini..." autocomplete="off"
                                       autofocus>
                                <button type="submit" class="btn btn-primary" id="sendButton">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="suggestQuestion()" 
                                        title="Suggest question">
                                    <i class="fas fa-lightbulb"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="clearChat()" 
                                        title="Clear chat">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            
                            <!-- Quick Questions -->
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">💡 Pertanyaan cepat:</small>
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Bagaimana cara menambah produk baru?">
                                        <i class="fas fa-plus me-1"></i>Tambah Produk
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Cara melihat stok produk?">
                                        <i class="fas fa-warehouse me-1"></i>Cek Stok
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Bagaimana membuat pembelian baru?">
                                        <i class="fas fa-shopping-cart me-1"></i>Buat Pembelian
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Bagaimana melihat dashboard report?">
                                        <i class="fas fa-chart-bar me-1"></i>Dashboard Report
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Berapa stok masing-masing produk?">
                                        <i class="fas fa-list-ol me-1"></i>Detail Stok
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Berapa banyak pembelian completed dan cancelled?">
                                        <i class="fas fa-chart-pie me-1"></i>Status Pembelian
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Hitung total semua stok produk">
                                        <i class="fas fa-calculator me-1"></i>Total Stok
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="quickQuestion(this)" 
                                            data-question="Cara membatalkan pembelian?">
                                        <i class="fas fa-undo me-1"></i>Batalkan Pembelian
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- System Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-cogs me-2"></i>System Status
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">🤖 AI Assistant Mode</small>
                        <span class="badge bg-info" id="aiModeStatus">Checking...</span>
                        <small class="text-muted d-block mt-1" id="aiModeDetails"></small>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">📡 API Connection</small>
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm text-primary me-2" id="apiSpinner"></div>
                            <span id="apiStatusText">Testing connection...</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">🏪 Store Statistics</small>
                        <div class="small" id="storeStats">
                            <div class="d-flex justify-content-between">
                                <span>Total Produk:</span>
                                <span class="fw-bold" id="statProducts">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Total Pembelian:</span>
                                <span class="fw-bold" id="statPurchases">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Produk Stok Rendah:</span>
                                <span class="fw-bold text-warning" id="statLowStock">0</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">⚡ Capabilities</small>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="badge bg-primary">🇮🇩 Bahasa Indonesia</span>
                            <span class="badge bg-success">📦 Product Help</span>
                            <span class="badge bg-success">📊 Stock Help</span>
                            <span class="badge bg-success">🛒 Purchase Help</span>
                            <span class="badge bg-success">📈 Report Help</span>
                            <span class="badge bg-info">🤖 DeepSeek AI</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small text-muted">
                        <p class="mb-1">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Powered by DeepSeek AI</strong>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-bolt me-1"></i>
                            Free tier: 100 requests/hour
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-clock me-1"></i>
                            Server time: {{ now()->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Help Topics -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-question-circle me-2"></i>Topik Bantuan
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="quickQuestion(this, 'Bagaimana menambah dan mengelola produk?')">
                            <i class="fas fa-box me-2 text-primary"></i>Manajemen Produk
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="quickQuestion(this, 'Cara mengontrol dan update stok produk?')">
                            <i class="fas fa-warehouse me-2 text-success"></i>Kontrol Stok
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="quickQuestion(this, 'Bagaimana proses pembelian dari awal?')">
                            <i class="fas fa-shopping-cart me-2 text-warning"></i>Proses Pembelian
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="quickQuestion(this, 'Bagaimana melihat laporan dan analytics?')">
                            <i class="fas fa-chart-bar me-2 text-info"></i>Laporan & Analytics
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" 
                           onclick="quickQuestion(this, 'Troubleshooting common issues?')">
                            <i class="fas fa-tools me-2 text-danger"></i>Troubleshooting
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chat-container {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .message {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .bot-message .message-content {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #0d6efd;
        border-radius: 0 12px 12px 12px;
        max-width: 85%;
    }
    
    .user-message .message-content {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        border-radius: 12px 0 12px 12px;
        max-width: 85%;
        margin-left: auto;
    }
    
    .chat-messages {
        scrollbar-width: thin;
        scrollbar-color: #adb5bd transparent;
    }
    
    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .chat-messages::-webkit-scrollbar-thumb {
        background-color: #adb5bd;
        border-radius: 3px;
    }
    
    .typing-indicator {
        display: inline-block;
        background-color: #f8f9fa;
        padding: 8px 16px;
        border-radius: 12px;
        border-left: 4px solid #6c757d;
    }
    
    .typing-indicator span {
        height: 8px;
        width: 8px;
        background: #6c757d;
        border-radius: 50%;
        display: inline-block;
        margin: 0 2px;
        animation: typing 1.4s infinite ease-in-out;
    }
    
    .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
    .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
    
    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-8px); }
    }
</style>
@endpush

@push('scripts')
<script>
/* ============================================================
    GLOBAL VARIABLES
   ============================================================ */
let isTyping = false;
let chatHistory = [];

/* ============================================================
    CHAT FUNCTIONS
   ============================================================ */

function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function addTypingIndicator() {
    if (isTyping) return;
    
    isTyping = true;
    const chatMessages = document.getElementById('chatMessages');
    const typingDiv = document.createElement('div');
    typingDiv.className = 'message bot-message mb-3';
    typingDiv.id = 'typingIndicator';
    typingDiv.innerHTML = `
        <div class="typing-indicator">
            <span></span><span></span><span></span>
        </div>
        <small class="text-muted ms-3">${getCurrentTime()}</small>
    `;
    chatMessages.appendChild(typingDiv);
    scrollToBottom();
}

function removeTypingIndicator() {
    const typingIndicator = document.getElementById('typingIndicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
    isTyping = false;
}

function getCurrentTime() {
    const now = new Date();
    return now.getHours().toString().padStart(2, '0') + ':' + 
           now.getMinutes().toString().padStart(2, '0');
}

// Add user message
function addUserMessage(message) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message user-message mb-3';
    messageDiv.innerHTML = `
        <div class="message-content bg-primary text-white p-3 rounded">
            <strong>Anda:</strong> ${message}
        </div>
        <small class="text-muted me-3">${getCurrentTime()}</small>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
    
    // Save to history
    chatHistory.push({
        role: 'user',
        message: message,
        time: getCurrentTime()
    });
}

// Add bot message
function addBotMessage(message, source = 'ai') {
    removeTypingIndicator();
    
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message bot-message mb-3';
    
    let sourceBadge = '';
    if (source === 'ai') {
        sourceBadge = ' <span class="badge bg-info badge-sm">AI</span>';
    } else {
        sourceBadge = ' <span class="badge bg-secondary badge-sm">Basic</span>';
    }
    
    messageDiv.innerHTML = `
        <div class="message-content bg-light p-3 rounded">
            <strong>🤖 Assistant${sourceBadge}:</strong> 
            <div class="mt-1">${message}</div>
        </div>
        <small class="text-muted ms-3">${getCurrentTime()}</small>
    `;
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
    
    // Save to history
    chatHistory.push({
        role: 'assistant',
        message: message,
        source: source,
        time: getCurrentTime()
    });
}

// Send message to server
function sendMessageToServer(message) {
    addTypingIndicator();
    
    fetch("{{ route('admin.chatbot.chat') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            addBotMessage(data.response, data.source);
            
            // Update stats if available
            if (data.stats) {
                updateStatsDisplay(data.stats);
            }
        } else {
            addBotMessage('Maaf, terjadi kesalahan. Silakan coba lagi.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        addBotMessage('Maaf, koneksi terputus. Silakan coba lagi.', 'error');
    })
    .finally(() => {
        removeTypingIndicator();
    });
}

// Quick question button
function quickQuestion(button, question = null) {
    const questionText = question || button.dataset.question;
    const messageInput = document.getElementById('messageInput');
    messageInput.value = questionText;
    document.getElementById('chatForm').dispatchEvent(new Event('submit'));
}

// Suggest random question
function suggestQuestion() {
    const questions = [
        "Bagaimana cara menambah produk baru?",
        "Cara melihat stok produk saat ini?",
        "Bagaimana membuat pembelian baru?",
        "Bagaimana melihat laporan penjualan?",
        "Cara membatalkan pembelian yang sudah dibuat?",
        "Bagaimana mengupdate stok produk?",
        "Apa saja fitur dashboard admin?",
        "Cara menghapus produk dari sistem?"
    ];
    
    const randomQuestion = questions[Math.floor(Math.random() * questions.length)];
    const messageInput = document.getElementById('messageInput');
    messageInput.value = randomQuestion;
    messageInput.focus();
}

// Clear chat
function clearChat() {
    if (confirm('Apakah Anda yakin ingin menghapus semua percakapan?')) {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML = '';
        chatHistory = [];
        
        // Add initial message back
        const initialMessage = document.createElement('div');
        initialMessage.className = 'message bot-message mb-3';
        initialMessage.innerHTML = `
            <div class="message-content bg-light p-3 rounded">
                <strong>🤖 AI Assistant:</strong> 
                <p class="mb-1">Halo! Percakapan telah direset.</p>
                <p class="mb-0">Silakan tanyakan apa yang Anda butuhkan! 😊</p>
            </div>
            <small class="text-muted ms-3">${getCurrentTime()}</small>
        `;
        chatMessages.appendChild(initialMessage);
        scrollToBottom();
    }
}

/* ============================================================
    SYSTEM STATUS FUNCTIONS
   ============================================================ */

function checkStatus() {
    const statusBtn = document.getElementById('connectionStatus');
    const apiSpinner = document.getElementById('apiSpinner');
    const apiStatusText = document.getElementById('apiStatusText');
    
    statusBtn.className = 'badge bg-warning float-end';
    statusBtn.textContent = '🟡 Checking...';
    apiSpinner.style.display = 'inline-block';
    apiStatusText.textContent = 'Testing connection...';
    
    fetch("{{ route('admin.chatbot.status') }}")
    .then(response => response.json())
    .then(data => {
        // Update AI mode status
        const aiModeStatus = document.getElementById('aiModeStatus');
        const aiModeDetails = document.getElementById('aiModeDetails');
        
        if (data.ai_enabled) {
            aiModeStatus.className = 'badge bg-success';
            aiModeStatus.textContent = '🤖 AI-Powered Mode';
            aiModeDetails.textContent = 'DeepSeek AI aktif';
        } else {
            aiModeStatus.className = 'badge bg-secondary';
            aiModeStatus.textContent = '📝 Rule-Based Mode';
            aiModeDetails.textContent = 'Menggunakan response dasar';
        }
        
        // Update API status
        if (data.api_key_configured && data.api_key_valid) {
            apiSpinner.style.display = 'none';
            apiStatusText.textContent = '✅ API Key valid';
            statusBtn.className = 'badge bg-success float-end';
            statusBtn.textContent = '🟢 Connected';
        } else if (data.api_key_configured) {
            apiSpinner.style.display = 'none';
            apiStatusText.textContent = '⚠️ API Key tidak valid';
            statusBtn.className = 'badge bg-warning float-end';
            statusBtn.textContent = '🟡 Limited';
        } else {
            apiSpinner.style.display = 'none';
            apiStatusText.textContent = '❌ API Key tidak dikonfigurasi';
            statusBtn.className = 'badge bg-danger float-end';
            statusBtn.textContent = '🔴 Disconnected';
        }
        
        // Update store stats
        if (data.store_stats) {
            updateStatsDisplay(data.store_stats);
        }
        
        // Show notification
        showNotification('System status updated', 'success');
    })
    .catch(error => {
        console.error('Status check error:', error);
        apiSpinner.style.display = 'none';
        apiStatusText.textContent = '❌ Connection failed';
        statusBtn.className = 'badge bg-danger float-end';
        statusBtn.textContent = '🔴 Error';
        
        showNotification('Failed to check system status', 'danger');
    });
}

function updateStatsDisplay(stats) {
    document.getElementById('statProducts').textContent = stats.total_products || 0;
    document.getElementById('statPurchases').textContent = stats.total_purchases || 0;
    document.getElementById('statLowStock').textContent = stats.low_stock_items || 0;
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

/* ============================================================
    EVENT LISTENERS
   ============================================================ */

document.addEventListener('DOMContentLoaded', function() {
    // Form submission
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        addUserMessage(message);
        messageInput.value = '';
        
        // Disable send button temporarily
        const sendButton = document.getElementById('sendButton');
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        sendMessageToServer(message);
        
        // Re-enable button after 2 seconds
        setTimeout(() => {
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }, 2000);
    });
    
    // Auto-focus input
    document.getElementById('messageInput').focus();
    
    // Check status on load
    setTimeout(checkStatus, 1000);
    
    // Enter key to send (but allow Shift+Enter for new line)
    document.getElementById('messageInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }
    });
    
    // Initial scroll
    scrollToBottom();
});

// Resize handling
window.addEventListener('resize', scrollToBottom);
</script>
@endpush
@endsection