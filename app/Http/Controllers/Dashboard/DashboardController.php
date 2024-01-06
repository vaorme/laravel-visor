<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller{
    public function index(){
        $ordersTotal = Order::where('status', 'COMPLETED')->sum('total');

        $viewData = [
            'orders_total' => $ordersTotal
        ];

        return view('dashboard.dashboard', $viewData);
    }
}
