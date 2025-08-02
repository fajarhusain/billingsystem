<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('status', 'active')->count(),
            'total_packages' => Package::where('is_active', true)->count(),
            'monthly_revenue' => Invoice::where('status', 'paid')
                ->whereMonth('payment_date', Carbon::now()->month)
                ->whereYear('payment_date', Carbon::now()->year)
                ->sum('amount'),
            'unpaid_invoices' => Invoice::where('status', 'unpaid')->count(),
            'overdue_invoices' => Invoice::where('status', 'unpaid')
                ->where('due_date', '<', Carbon::now())
                ->count(),
        ];

        $recentInvoices = Invoice::with('customer', 'customer.package')
            ->latest()
            ->take(10)
            ->get();

        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereYear('payment_date', Carbon::now()->year)
            ->selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard.index', compact('stats', 'recentInvoices', 'monthlyRevenue'));
    }
}