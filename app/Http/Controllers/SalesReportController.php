<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

// Mostly static data and placeholders for now
// In a real application, replace with actual database queries and business logic

class SalesReportController extends Controller
{
    public function index()
    {
    return view('sales.report');
    }

    public function smsIndex()
    {
        return view('sales.sms.index');
    }

    public function getSummaryData(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'report_type' => 'nullable|in:daily,weekly,monthly,yearly,custom'
        ]);

        // In a real application, fetch from database
        // $summary = Order::whereBetween('created_at', [$fromDate, $toDate])
        //     ->selectRaw('
        //         SUM(total_amount) as total_revenue,
        //         COUNT(*) as total_orders,
        //         AVG(total_amount) as average_order,
        //         COUNT(DISTINCT customer_id) as total_customers
        //     ')->first();

        return response()->json([
            'success' => true,
            'summary' => [
                'total_revenue' => 24580.50,
                'total_orders' => 1245,
                'average_order' => 19.75,
                'total_customers' => 892,
                'growth' => [
                    'revenue' => 12.5,
                    'orders' => 8.3,
                    'avg_order' => 3.2,
                    'customers' => 15.7
                ]
            ]
        ]);
    }

    public function getSalesTrend(Request $request)
    {
        $request->validate([
            'period' => 'required|in:daily,weekly,monthly',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        $period = $request->period;

        // Sample data based on period
        $trendData = [
            'daily' => [
                'labels' => ['Jan 1', 'Jan 2', 'Jan 3', 'Jan 4', 'Jan 5', 'Jan 6', 'Jan 7'],
                'data' => [850, 920, 1100, 980, 1250, 1380, 1150]
            ],
            'weekly' => [
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'data' => [5800, 6200, 7100, 6500]
            ],
            'monthly' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [24500, 26800, 29200, 27600, 31000, 28900]
            ]
        ];

        return response()->json([
            'success' => true,
            'trend' => $trendData[$period] ?? $trendData['daily']
        ]);
    }

    public function getCategoryPerformance(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        // In a real application:
        // $categories = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
        //     ->join('categories', 'products.category_id', '=', 'categories.id')
        //     ->whereBetween('order_items.created_at', [$fromDate, $toDate])
        //     ->groupBy('categories.name')
        //     ->selectRaw('categories.name, SUM(order_items.quantity * order_items.price) as total_sales')
        //     ->orderBy('total_sales', 'desc')
        //     ->get();

        return response()->json([
            'success' => true,
            'categories' => [
                'labels' => ['Coffee', 'Pastry', 'Food', 'Beverage'],
                'data' => [45, 25, 20, 10],
                'colors' => ['#8B4513', '#ffc107', '#28a745', '#17a2b8'],
                'revenue' => [11061.23, 6145.13, 4916.10, 2458.05]
            ]
        ]);
    }

    public function getTopProducts(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'limit' => 'nullable|integer|min:1|max:20'
        ]);

        $limit = $request->limit ?? 10;

        // In a real application:
        // $topProducts = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
        //     ->join('categories', 'products.category_id', '=', 'categories.id')
        //     ->whereBetween('order_items.created_at', [$fromDate, $toDate])
        //     ->groupBy('products.id', 'products.name', 'categories.name')
        //     ->selectRaw('
        //         products.name,
        //         categories.name as category,
        //         SUM(order_items.quantity * order_items.price) as total_sales,
        //         SUM(order_items.quantity) as total_quantity
        //     ')
        //     ->orderBy('total_sales', 'desc')
        //     ->limit($limit)
        //     ->get();

        return response()->json([
            'success' => true,
            'products' => [
                ['name' => 'Cappuccino', 'category' => 'Coffee', 'sales' => 4250.00, 'quantity' => 185],
                ['name' => 'Croissant', 'category' => 'Pastry', 'sales' => 2890.50, 'quantity' => 142],
                ['name' => 'Latte', 'category' => 'Coffee', 'sales' => 3650.75, 'quantity' => 156],
                ['name' => 'Caesar Salad', 'category' => 'Food', 'sales' => 1980.25, 'quantity' => 89],
                ['name' => 'Americano', 'category' => 'Coffee', 'sales' => 2340.00, 'quantity' => 98],
                ['name' => 'Blueberry Muffin', 'category' => 'Pastry', 'sales' => 1650.75, 'quantity' => 75],
                ['name' => 'Grilled Chicken Sandwich', 'category' => 'Food', 'sales' => 1420.50, 'quantity' => 58],
                ['name' => 'Iced Coffee', 'category' => 'Beverage', 'sales' => 1280.25, 'quantity' => 64],
                ['name' => 'Chocolate Cake', 'category' => 'Pastry', 'sales' => 945.00, 'quantity' => 35],
                ['name' => 'Green Tea', 'category' => 'Beverage', 'sales' => 720.50, 'quantity' => 48]
            ]
        ]);
    }

    public function getPaymentMethods(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        // In a real application:
        // $paymentMethods = Order::whereBetween('created_at', [$fromDate, $toDate])
        //     ->groupBy('payment_method')
        //     ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total_amount')
        //     ->get();

        return response()->json([
            'success' => true,
            'payment_methods' => [
                'labels' => ['Cash', 'Card', 'Digital Wallet'],
                'data' => [40, 45, 15],
                'colors' => ['#28a745', '#1572e8', '#ffc107'],
                'amounts' => [9832.20, 11061.23, 3687.08],
                'counts' => [498, 560, 187]
            ]
        ]);
    }

    public function getHourlyPattern(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        // In a real application:
        // $hourlyData = Order::whereBetween('created_at', [$fromDate, $toDate])
        //     ->selectRaw('HOUR(created_at) as hour, COUNT(*) as orders, SUM(total_amount) as revenue')
        //     ->groupBy('hour')
        //     ->orderBy('hour')
        //     ->get();

        return response()->json([
            'success' => true,
            'hourly_pattern' => [
                'labels' => ['6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM', '4 PM', '5 PM'],
                'orders' => [45, 120, 280, 450, 380, 320, 290, 520, 480, 360, 290, 250],
                'revenue' => [890.50, 2380.40, 5560.80, 8910.50, 7524.60, 6336.80, 5742.20, 10296.40, 9504.80, 7128.60, 5742.50, 4950.25]
            ]
        ]);
    }

    public function getCustomerInsights(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date'
        ]);

        // In a real application:
        // $vipCustomers = Customer::whereHas('orders', function($query) use ($fromDate, $toDate) {
        //     $query->whereBetween('created_at', [$fromDate, $toDate]);
        // }, '>=', 10)->count();

        return response()->json([
            'success' => true,
            'insights' => [
                [
                    'icon' => 'fas fa-crown',
                    'title' => 'VIP Customers',
                    'description' => 'Customers with 10+ orders',
                    'value' => '127',
                    'trend' => '+12.5%'
                ],
                [
                    'icon' => 'fas fa-user-plus',
                    'title' => 'New Customers',
                    'description' => 'First-time buyers this month',
                    'value' => '89',
                    'trend' => '+23.1%'
                ],
                [
                    'icon' => 'fas fa-redo',
                    'title' => 'Return Rate',
                    'description' => 'Customer retention percentage',
                    'value' => '73%',
                    'trend' => '+2.8%'
                ],
                [
                    'icon' => 'fas fa-star',
                    'title' => 'Avg Rating',
                    'description' => 'Customer satisfaction score',
                    'value' => '4.8',
                    'trend' => '+0.3'
                ]
            ]
        ]);
    }

    public function getSalesData(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'category' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100'
        ]);

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 10;

        // In a real application:
        // $query = Order::with(['customer', 'orderItems.product'])
        //     ->whereBetween('created_at', [$fromDate, $toDate]);
        
        // if ($request->category) {
        //     $query->whereHas('orderItems.product.category', function($q) use ($request) {
        //         $q->where('name', $request->category);
        //     });
        // }
        
        // if ($request->payment_method) {
        //     $query->where('payment_method', $request->payment_method);
        // }
        
        // $orders = $query->orderBy('created_at', 'desc')
        //     ->paginate($perPage, ['*'], 'page', $page);

        // Sample data
        $sampleData = [
            [
                'id' => 1,
                'date' => '2024-01-15 14:30:00',
                'order_id' => 'ORD-001',
                'customer' => 'John Doe',
                'customer_email' => 'john@example.com',
                'items' => 'Cappuccino, Croissant',
                'category' => 'Coffee & Pastry',
                'payment_method' => 'Card',
                'amount' => 12.50,
                'status' => 'completed'
            ],
            [
                'id' => 2,
                'date' => '2024-01-15 15:45:00',
                'order_id' => 'ORD-002',
                'customer' => 'Jane Smith',
                'customer_email' => 'jane@example.com',
                'items' => 'Latte, Caesar Salad',
                'category' => 'Coffee & Food',
                'payment_method' => 'Cash',
                'amount' => 18.75,
                'status' => 'completed'
            ],
            [
                'id' => 3,
                'date' => '2024-01-15 16:20:00',
                'order_id' => 'ORD-003',
                'customer' => 'Mike Johnson',
                'customer_email' => 'mike@example.com',
                'items' => 'Americano, Blueberry Muffin, Green Tea',
                'category' => 'Mixed',
                'payment_method' => 'Digital Wallet',
                'amount' => 22.25,
                'status' => 'completed'
            ],
            [
                'id' => 4,
                'date' => '2024-01-15 17:10:00',
                'order_id' => 'ORD-004',
                'customer' => 'Sarah Wilson',
                'customer_email' => 'sarah@example.com',
                'items' => 'Iced Coffee, Chocolate Cake',
                'category' => 'Beverage & Pastry',
                'payment_method' => 'Card',
                'amount' => 15.80,
                'status' => 'completed'
            ],
            [
                'id' => 5,
                'date' => '2024-01-15 18:35:00',
                'order_id' => 'ORD-005',
                'customer' => 'David Brown',
                'customer_email' => 'david@example.com',
                'items' => 'Grilled Chicken Sandwich',
                'category' => 'Food',
                'payment_method' => 'Cash',
                'amount' => 24.50,
                'status' => 'completed'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $sampleData,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => 245, // Total records
                'last_page' => ceil(245 / $perPage),
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, 245)
            ]
        ]);
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:daily,weekly,monthly,yearly,custom',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'category' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'format' => 'nullable|in:pdf,excel,csv'
        ]);

        $format = $request->format ?? 'pdf';
        $reportType = $request->report_type;

        // In a real application, generate actual report
        // switch($format) {
        //     case 'pdf':
        //         return $this->generatePdfReport($request);
        //     case 'excel':
        //         return $this->generateExcelReport($request);
        //     case 'csv':
        //         return $this->generateCsvReport($request);
        // }

        return response()->json([
            'success' => true,
            'message' => "Report generated successfully in {$format} format",
            'report_type' => $reportType,
            'download_url' => "/reports/sales-{$reportType}-" . now()->format('Y-m-d') . ".{$format}",
            'file_name' => "sales-{$reportType}-" . now()->format('Y-m-d') . ".{$format}"
        ]);
    }

    public function exportData(Request $request)
    {
        $request->validate([
            'format' => 'required|in:excel,csv,pdf',
            'include_summary' => 'boolean',
            'include_charts' => 'boolean',
            'include_details' => 'boolean',
            'email_to' => 'nullable|email'
        ]);

        $format = $request->format;
        $fileName = 'sales-report-' . now()->format('Y-m-d-H-i-s') . '.' . $format;

        // In a real application, generate and return file
        // if ($request->email_to) {
        //     Mail::to($request->email_to)->send(new SalesReportMail($fileName));
        // }

        return response()->json([
            'success' => true,
            'message' => "Report exported successfully in {$format} format",
            'file_name' => $fileName,
            'download_url' => "/downloads/reports/{$fileName}",
            'email_sent' => $request->email_to ? true : false
        ]);
    }

    public function getOrderDetails($orderId)
    {
        // In a real application:
        // $order = Order::with(['customer', 'orderItems.product'])
        //     ->findOrFail($orderId);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $orderId,
                'order_number' => 'ORD-' . str_pad($orderId, 3, '0', STR_PAD_LEFT),
                'date' => '2024-01-15 14:30:00',
                'customer' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'phone' => '+1234567890'
                ],
                'items' => [
                    [
                        'name' => 'Cappuccino',
                        'quantity' => 1,
                        'price' => 4.50,
                        'total' => 4.50
                    ],
                    [
                        'name' => 'Croissant',
                        'quantity' => 2,
                        'price' => 4.00,
                        'total' => 8.00
                    ]
                ],
                'subtotal' => 12.50,
                'tax' => 1.25,
                'total' => 13.75,
                'payment_method' => 'Card',
                'status' => 'completed'
            ]
        ]);
    }

    public function printReceipt($orderId)
    {
        // In a real application, generate receipt
        return response()->json([
            'success' => true,
            'message' => 'Receipt sent to printer',
            'order_id' => $orderId,
            'receipt_number' => 'RCP-' . time()
        ]);
    }

    public function getDashboardStats()
    {
        // Quick stats for dashboard widgets
        return response()->json([
            'success' => true,
            'stats' => [
                'today_sales' => 1580.50,
                'today_orders' => 67,
                'yesterday_sales' => 1420.25,
                'yesterday_orders' => 58,
                'monthly_sales' => 24580.50,
                'monthly_orders' => 1245,
                'top_product_today' => 'Cappuccino',
                'peak_hour_today' => '2:00 PM'
            ]
        ]);
    }

    public function getRealtimeUpdates()
    {
        // For real-time dashboard updates
        return response()->json([
            'success' => true,
            'updates' => [
                'last_order_time' => now()->subMinutes(3)->toISOString(),
                'orders_last_hour' => 12,
                'revenue_last_hour' => 245.75,
                'active_customers' => 8,
                'pending_orders' => 3
            ]
        ]);
    }
}
