<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    private $apiKey;
    private $endpoint = 'https://api.deepseek.com/v1/chat/completions';
    
    public function __construct()
    {
        $this->apiKey = env('DEEPSEEK_API_KEY');
        
        // Debug: Log jika API key ada atau tidak
        if (!empty($this->apiKey)) {
            Log::info('DeepSeek API Key loaded, length: ' . strlen($this->apiKey));
        } else {
            Log::warning('DeepSeek API Key is empty or not configured');
        }
    }
    
    public function index()
    {
        return view('admin.chatbot.index');
    }
    
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);
        
        $userMessage = $request->input('message');
        Log::info('Chatbot query: ' . $userMessage);
        
        try {
            // Get basic store data dulu (tanpa detail yang kompleks)
            $storeData = $this->getBasicStoreData();
            
            // Cek apakah AI mode aktif
            $useAI = !empty($this->apiKey) && env('CHATBOT_USE_AI', true);
            
            if ($useAI) {
                $aiResponse = $this->callDeepSeekAPI($userMessage, $storeData);
                
                return response()->json([
                    'success' => true,
                    'response' => $aiResponse,
                    'timestamp' => now()->format('H:i'),
                    'source' => 'deepseek-ai',
                    'stats' => $storeData['summary']
                ]);
            } else {
                throw new \Exception('AI mode disabled or API key not configured');
            }
            
        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            
            // Fallback ke rule-based dengan data yang sudah ada
            try {
                $storeData = $this->getBasicStoreData();
                $ruleBasedResponse = $this->getEnhancedRuleBasedResponse($userMessage, $storeData);
            } catch (\Exception $fallbackError) {
                Log::error('Fallback error: ' . $fallbackError->getMessage());
                $ruleBasedResponse = "🤖 **FurnitureBot Assistant**\n\nMaaf, terjadi kesalahan sistem. Silakan coba lagi nanti.";
            }
            
            return response()->json([
                'success' => true,
                'response' => $ruleBasedResponse,
                'timestamp' => now()->format('H:i'),
                'source' => 'rule-based',
                'stats' => isset($storeData['summary']) ? $storeData['summary'] : []
            ]);
        }
    }
    
    /**
     * Ambil data toko yang SIMPLE dulu untuk testing
     */
    private function getBasicStoreData()
    {
        try {
            // 1. Summary Statistics - SIMPLE VERSION
            $summary = [
                'total_products' => Product::count(),
                'total_purchases' => Purchase::count(),
                'completed_purchases' => Purchase::where('status', 'completed')->count(),
                'cancelled_purchases' => Purchase::where('status', 'cancelled')->count(),
                'pending_purchases' => Purchase::where('status', 'pending')->count(),
                'total_revenue' => (float) Purchase::where('status', 'completed')->sum('total_amount'),
                'low_stock_items' => 0, // Sementara 0
                'today_purchases' => Purchase::whereDate('purchase_date', today())
                    ->where('status', 'completed')->count(),
                'today_revenue' => (float) Purchase::whereDate('purchase_date', today())
                    ->where('status', 'completed')->sum('total_amount'),
                'out_of_stock' => 0, // Sementara 0
            ];
            
            // 2. Product Stock Details - LIMIT untuk testing
            $productStocks = collect([]); // Kosong dulu
            
            // 3. Purchase Status Breakdown
            $purchaseStatus = Purchase::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();
            
            return [
                'summary' => $summary,
                'product_stocks' => $productStocks,
                'purchase_status' => $purchaseStatus,
                'data_timestamp' => now()->format('Y-m-d H:i:s')
            ];
            
        } catch (\Exception $e) {
            Log::error('Error getting basic store data: ' . $e->getMessage());
            
            // Return data default jika error
            return [
                'summary' => [
                    'total_products' => 0,
                    'total_purchases' => 0,
                    'completed_purchases' => 0,
                    'cancelled_purchases' => 0,
                    'pending_purchases' => 0,
                    'total_revenue' => 0,
                    'low_stock_items' => 0,
                    'today_purchases' => 0,
                    'today_revenue' => 0,
                    'out_of_stock' => 0,
                ],
                'product_stocks' => collect([]),
                'purchase_status' => [],
                'data_timestamp' => now()->format('Y-m-d H:i:s')
            ];
        }
    }
    
    /**
     * Ambil data toko DETAIL setelah basic berhasil
     */
    private function getDetailedStoreData()
    {
        try {
            // Gunakan getBasicStoreData dulu
            $basicData = $this->getBasicStoreData();
            
            // Coba ambil detail tambahan jika basic data berhasil
            if ($basicData['summary']['total_products'] > 0) {
                // Ambil product stocks (maksimal 10 untuk efisiensi)
                $productStocks = Product::with(['stock', 'category'])
                    ->orderBy('name')
                    ->limit(10)
                    ->get()
                    ->map(function($product) {
                        $stockQty = $product->stock ? $product->stock->quantity : 0;
                        return [
                            'name' => $product->name,
                            'stock' => $stockQty,
                            'category' => $product->category->name ?? 'Uncategorized',
                            'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                            'status' => $stockQty == 0 ? 'Habis' : 
                                      ($stockQty <= 5 ? 'Rendah' : 'Aman')
                        ];
                    });
                
                // Hitung low stock dan out of stock berdasarkan data real
                $lowStockCount = $productStocks->where('stock', '<=', 5)->where('stock', '>', 0)->count();
                $outOfStockCount = $productStocks->where('stock', '==', 0)->count();
                
                // Update summary dengan data real
                $basicData['summary']['low_stock_items'] = $lowStockCount;
                $basicData['summary']['out_of_stock'] = $outOfStockCount;
                
                // Simpan product stocks
                $basicData['product_stocks'] = $productStocks;
            }
            
            return $basicData;
            
        } catch (\Exception $e) {
            Log::error('Error getting detailed store data: ' . $e->getMessage());
            return $this->getBasicStoreData(); // Fallback ke basic
        }
    }
    
    private function callDeepSeekAPI($message, $storeData)
    {
        try {
            // System prompt yang lebih sederhana untuk testing
            $summary = $storeData['summary'];
            
            $systemPrompt = "You are FurnitureBot, an AI assistant for a furniture store admin system.

STORE INFORMATION:
- Store: Premium Furniture Store Admin System
- Data Timestamp: {$storeData['data_timestamp']}

CURRENT STORE STATISTICS:
• Total Products: {$summary['total_products']}
• Total Purchases: {$summary['total_purchases']}
• Completed Purchases: {$summary['completed_purchases']}
• Cancelled Purchases: {$summary['cancelled_purchases']}
• Pending Purchases: {$summary['pending_purchases']}
• Total Revenue: Rp " . number_format($summary['total_revenue'], 0, ',', '.') . "
• Low Stock Items (≤5 units): {$summary['low_stock_items']}
• Out of Stock Items: {$summary['out_of_stock']}
• Today's Purchases: {$summary['today_purchases']}
• Today's Revenue: Rp " . number_format($summary['today_revenue'], 0, ',', '.') . "

RESPONSE GUIDELINES:
1. Use Bahasa Indonesia (Indonesian language)
2. Be helpful, professional, and friendly
3. Use the store statistics above to answer questions accurately
4. If asked about stock details, provide general information based on the statistics
5. If asked about purchase status, use the completed/cancelled/pending numbers
6. You can calculate percentages and totals based on the provided data
7. Format responses clearly with bullet points when appropriate
8. Use emojis: 📦 🛒 📊 📈 ⚠️ ✅
9. If you need more specific data, suggest the user check the specific menu
10. Keep responses informative but concise

IMPORTANT: Always respond in Bahasa Indonesia unless specifically asked in another language.";

            Log::info('Calling DeepSeek API with system prompt');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post($this->endpoint, [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $message]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
                'top_p' => 0.9,
                'stream' => false
            ]);

            Log::info('DeepSeek API Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['choices'][0]['message']['content'])) {
                    $content = $data['choices'][0]['message']['content'];
                    Log::info('DeepSeek Response received, length: ' . strlen($content) . ' chars');
                    return $this->formatResponse($content);
                }
                
                throw new \Exception('Invalid response format from AI API');
            }
            
            $errorBody = $response->body();
            Log::error('DeepSeek API Error - Status: ' . $response->status() . ', Body: ' . substr($errorBody, 0, 200));
            throw new \Exception('API request failed with status: ' . $response->status());
            
        } catch (\Exception $e) {
            Log::error('API Call Exception: ' . $e->getMessage());
            throw $e; // Re-throw untuk ditangani di caller
        }
    }
    
    private function formatResponse($response)
    {
        try {
            // Clean and format the response
            $response = trim($response);
            
            // Convert markdown formatting to HTML
            $response = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $response);
            $response = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $response);
            
            // Format lists
            $response = preg_replace('/^[-•]\s*(.*)$/m', '• $1', $response);
            $response = preg_replace('/^(\d+)\.\s*(.*)$/m', '$1. $2', $response);
            
            // Add line breaks
            $response = nl2br($response);
            
            return $response;
        } catch (\Exception $e) {
            Log::error('Error formatting response: ' . $e->getMessage());
            return nl2br($response); // Return as-is jika error formatting
        }
    }
    
    private function getEnhancedRuleBasedResponse($message, $storeData)
    {
        $message = strtolower(trim($message));
        $summary = $storeData['summary'];
        
        // Check for specific data queries
        if (str_contains($message, 'stok') && 
            (str_contains($message, 'barang') || str_contains($message, 'produk'))) {
            
            // Jika ada data produk, tampilkan
            if ($storeData['product_stocks']->isNotEmpty()) {
                $productList = $storeData['product_stocks']->take(8)->map(function($product) {
                    return "• {$product['name']}: {$product['stock']} unit ({$product['status']})";
                })->implode("\n");
                
                $totalStock = $storeData['product_stocks']->sum('stock');
                
                return "📦 **Detail Stok Produk**\n\n" .
                       "Total stok: {$totalStock} unit\n\n" .
                       "**Daftar Stok Produk:**\n{$productList}\n\n" .
                       "📊 **Summary:**\n" .
                       "• Total Produk: {$summary['total_products']}\n" .
                       "• Stok Rendah (≤5): {$summary['low_stock_items']} produk\n" .
                       "• Stok Habis: {$summary['out_of_stock']} produk";
            } else {
                return "📦 **Informasi Stok**\n\n" .
                       "• Total Produk: {$summary['total_products']}\n" .
                       "• Stok Rendah (≤5): {$summary['low_stock_items']} produk\n" .
                       "• Stok Habis: {$summary['out_of_stock']} produk\n\n" .
                       "Untuk melihat detail stok masing-masing produk:\n" .
                       "1. Buka menu **Produk** → **Manajemen Stok**\n" .
                       "2. Atau tanyakan dengan lebih spesifik, misal: 'Stok kursi sofa berapa?'";
            }
        }
        
        if (str_contains($message, 'pembelian') && 
            (str_contains($message, 'completed') || str_contains($message, 'cancel') || str_contains($message, 'status'))) {
            
            $completed = $summary['completed_purchases'];
            $cancelled = $summary['cancelled_purchases'];
            $pending = $summary['pending_purchases'];
            $total = $summary['total_purchases'];
            
            $completedPercent = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
            $cancelledPercent = $total > 0 ? round(($cancelled / $total) * 100, 1) : 0;
            
            return "🛒 **Status Pembelian**\n\n" .
                   "**Total Semua Pembelian:** {$total}\n\n" .
                   "**Breakdown Status:**\n" .
                   "• ✅ Completed: {$completed} ({$completedPercent}%)\n" .
                   "• ❌ Cancelled: {$cancelled} ({$cancelledPercent}%)\n" .
                   "• ⏳ Pending: {$pending}\n\n" .
                   "**Hari Ini:**\n" .
                   "• Pembelian: {$summary['today_purchases']}\n" .
                   "• Revenue: Rp " . number_format($summary['today_revenue'], 0, ',', '.') . "\n\n" .
                   "📈 **Success Rate:** {$completedPercent}%";
        }
        
        if (str_contains($message, 'total pembelian') || str_contains($message, 'berapa pembelian')) {
            return "📊 **Total Pembelian**\n\n" .
                   "**Statistik Pembelian:**\n" .
                   "• Total Semua: {$summary['total_purchases']}\n" .
                   "• Completed: {$summary['completed_purchases']}\n" .
                   "• Cancelled: {$summary['cancelled_purchases']}\n" .
                   "• Pending: {$summary['pending_purchases']}\n" .
                   "• Hari Ini: {$summary['today_purchases']}\n\n" .
                   "**Statistik Revenue:**\n" .
                   "• Total Revenue: Rp " . number_format($summary['total_revenue'], 0, ',', '.') . "\n" .
                   "• Revenue Hari Ini: Rp " . number_format($summary['today_revenue'], 0, ',', '.');
        }
        
        if (str_contains($message, 'total stok') || str_contains($message, 'stok semua')) {
            // Hitung total stok jika ada data
            $totalStock = 0;
            if ($storeData['product_stocks']->isNotEmpty()) {
                $totalStock = $storeData['product_stocks']->sum('stock');
            }
            
            $avgStock = $summary['total_products'] > 0 ? 
                round($totalStock / $summary['total_products'], 1) : 0;
            
            return "📦 **Total Stok Produk**\n\n" .
                   "**Statistik Stok:**\n" .
                   "• Total Produk: {$summary['total_products']}\n" .
                   "• Total Stok: {$totalStock} unit\n" .
                   "• Rata-rata Stok/Produk: {$avgStock} unit\n" .
                   "• Stok Rendah (≤5): {$summary['low_stock_items']} produk\n" .
                   "• Stok Habis: {$summary['out_of_stock']} produk\n\n" .
                   "Untuk analisis stok lebih detail:\n" .
                   "1. Buka menu **Produk** → **Manajemen Stok**\n" .
                   "2. Export data ke Excel untuk perhitungan lanjutan";
        }
        
        // Default response dengan data terkini
        $completedPercent = $summary['total_purchases'] > 0 ? 
            round(($summary['completed_purchases'] / $summary['total_purchases']) * 100, 1) : 0;
        
        return "🤖 **FurnitureBot Assistant**\n\n" .
               "Halo! Berikut data terkini toko furnitur Anda:\n\n" .
               "📊 **STATISTIK TOKO SAAT INI:**\n" .
               "• Total Produk: {$summary['total_products']}\n" .
               "• Total Pembelian: {$summary['total_purchases']}\n" .
               "  ✅ Completed: {$summary['completed_purchases']} ({$completedPercent}%)\n" .
               "  ❌ Cancelled: {$summary['cancelled_purchases']}\n" .
               "  ⏳ Pending: {$summary['pending_purchases']}\n" .
               "• Total Revenue: Rp " . number_format($summary['total_revenue'], 0, ',', '.') . "\n" .
               "• Stok Rendah: {$summary['low_stock_items']} produk\n" .
               "• Stok Habis: {$summary['out_of_stock']} produk\n" .
               "• Pembelian Hari Ini: {$summary['today_purchases']}\n" .
               "• Revenue Hari Ini: Rp " . number_format($summary['today_revenue'], 0, ',', '.') . "\n\n" .
               "**Saya dapat membantu dengan:**\n\n" .
               "📦 **Stok per Produk** - Lihat stok masing-masing barang\n" .
               "🛒 **Status Pembelian** - Cek completed, cancelled, pending\n" .
               "📊 **Total & Rata-rata** - Hitung total stok dan pembelian\n" .
               "📈 **Analisis Persentase** - Success rate, dll.\n\n" .
               "Silakan tanyakan data spesifik yang Anda butuhkan! 😊";
    }
    
    public function status()
    {
        try {
            $apiKey = env('DEEPSEEK_API_KEY');
            $hasApiKey = !empty($apiKey);
            
            // Ambil data SIMPLE untuk status check
            $storeData = $this->getBasicStoreData();
            $summary = $storeData['summary'];
            
            return response()->json([
                'ai_enabled' => $hasApiKey && env('CHATBOT_USE_AI', true),
                'api_key_configured' => $hasApiKey,
                'api_key_valid' => $hasApiKey && strlen($apiKey) > 30,
                'provider' => 'DeepSeek AI',
                'model' => 'deepseek-chat',
                'mode' => $hasApiKey ? 'AI-Powered' : 'Rule-Based',
                'rate_limit' => '100 requests/hour (free tier)',
                'status' => 'operational',
                'store_stats' => [
                    'total_products' => (int) $summary['total_products'],
                    'total_purchases' => (int) $summary['total_purchases'],
                    'completed_purchases' => (int) $summary['completed_purchases'],
                    'cancelled_purchases' => (int) $summary['cancelled_purchases'],
                    'pending_purchases' => (int) $summary['pending_purchases'],
                    'total_revenue' => (float) $summary['total_revenue'],
                    'low_stock_items' => (int) $summary['low_stock_items'],
                    'today_purchases' => (int) $summary['today_purchases'],
                    'today_revenue' => (float) $summary['today_revenue'],
                    'out_of_stock' => (int) $summary['out_of_stock']
                ],
                'data_timestamp' => $storeData['data_timestamp'],
                'server_time' => now()->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Status endpoint error: ' . $e->getMessage());
            
            // Return error response yang masih valid JSON
            return response()->json([
                'ai_enabled' => false,
                'api_key_configured' => false,
                'api_key_valid' => false,
                'provider' => 'DeepSeek AI',
                'model' => 'deepseek-chat',
                'mode' => 'Error Mode',
                'rate_limit' => 'N/A',
                'status' => 'error',
                'error' => $e->getMessage(),
                'store_stats' => [],
                'server_time' => now()->format('Y-m-d H:i:s')
            ], 200); // Tetap return 200 agar frontend tidak error
        }
    }
    
    public function testApi()
    {
        try {
            if (empty($this->apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'API key not configured in .env file',
                    'key_preview' => env('DEEPSEEK_API_KEY') ? substr(env('DEEPSEEK_API_KEY'), 0, 10) . '...' : 'Empty'
                ]);
            }
            
            Log::info('Testing DeepSeek API with key: ' . substr($this->apiKey, 0, 10) . '...');
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($this->endpoint, [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'user', 'content' => 'Hello, respond with just "OK" if you are working.']
                ],
                'max_tokens' => 10
            ]);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'API Key is valid and working',
                    'status' => $response->status(),
                    'response_time' => $response->handlerStats()['total_time'] ?? 'N/A'
                ]);
            } else {
                Log::error('API Test Failed - Status: ' . $response->status() . ', Body: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'API Error - Check API key validity',
                    'status' => $response->status(),
                    'error' => substr($response->body(), 0, 200)
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('API Test Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage(),
                'type' => get_class($e)
            ]);
        }
    }
}