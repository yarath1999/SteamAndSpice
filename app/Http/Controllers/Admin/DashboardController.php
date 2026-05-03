<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $hasMenuItemsTable = Schema::hasTable('menu_items');
        $hasOrdersTable = Schema::hasTable('orders');

        $stats = [
            'menu_items' => $hasMenuItemsTable ? (int) rescue(fn () => MenuItem::query()->count(), 0, false) : 0,
            'orders' => $hasOrdersTable ? (int) rescue(fn () => Order::query()->count(), 0, false) : 0,
            'pending_orders' => $hasOrdersTable ? (int) rescue(fn () => Order::query()->where('status', 'pending')->count(), 0, false) : 0,
        ];

        $latestOrders = $hasOrdersTable
            ? rescue(fn () => Order::query()->latest()->take(5)->get(), collect(), false)
            : collect();

        return view('admin.dashboard', compact('stats', 'latestOrders'));
    }
}
