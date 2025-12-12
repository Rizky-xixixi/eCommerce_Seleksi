<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AuthController,
    DashboardController,
    ProductController,
    PurchaseController,
    DocumentationController,
    ChatbotController,
};

// Public routes
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);

// Admin protected routes
Route::middleware(['web', 'admin.auth'])->prefix('admin')->name('admin.')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', ProductController::class)->except(['show']);
    Route::get('/products/stock', [ProductController::class, 'stock'])->name('products.stock');
    Route::post('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    
    // Purchases
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update']);
    Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
        
    // Chatbot
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
    Route::get('/chatbot/status', [ChatbotController::class, 'status'])->name('chatbot.status');
    Route::get('/chatbot/test-api', [ChatbotController::class, 'testApi'])->name('chatbot.test-api');
    
    // Redirect admin root to dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation.index');
});

// Home page redirect
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Fallback route
Route::fallback(function () {
    return redirect()->route('admin.login');
});