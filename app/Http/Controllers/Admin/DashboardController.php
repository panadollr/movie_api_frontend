<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Admin;

class DashboardController
{
    public function generalInformation(){
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = OrderDetail::join('products', 'products.id', 'order_details.product_id')
        ->sum(\DB::raw('products.new_price * order_details.product_quantity'));
        $totalUsers = \App\Models\User::count();

        return [
            ['title' => "Tổng sản phẩm", 'total_count' => $totalProducts],
            ['title' => "Tổng đơn hàng", 'total_count' => $totalOrders],
            ['title' => "Tổng doanh thu", 'total_count' => number_format($totalRevenue, 0, ',', '.') . ' đ'],
            ['title' => "Tổng người dùng ", 'total_count' => $totalUsers],
        ];
    }

    public function topProducts(){
         function handleData($inputData) {
            $product_ids = $inputData->pluck('product_id')->toArray();
            $result  = array_fill_keys($product_ids, ['total' => 0]);
                foreach($inputData as $com){
                    $result [$com->product_id]["name"] = $com->product_name;
                    $result [$com->product_id]["order_id"] = $com->order_id;
                    $result [$com->product_id]["product_quantity"] = $com->product_quantity;
                    $result [$com->product_id]["order_date"] = $com->order_date;
                    if (isset($result [$com->product_id])) {
                        $result [$com->product_id]["total"] += ($com->product_quantity*$com->new_price);
                    }
                }
                foreach ($result  as &$item) {
                    $item['total'] = number_format($item['total'], 0, ',', '.') . ' đ';
                }
            $result  = array_values($result);
            $first10Elements = array_slice($result, 0, 10);

            return $first10Elements;
        }

        $top_products_by_day = Product::join('order_details', 'order_details.product_id', '=', 'products.id')
        ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
        ->select('order_details.product_id as product_id', 'orders.order_id as order_id', 
        'orders.order_date as order_date', 'products.name as product_name',
        'products.new_price as new_price')
        ->selectRaw('SUM(order_details.product_quantity) as product_quantity')
        ->groupBy('product_id', 'product_name', 'new_price', 'product_quantity', 'order_date', 'order_id')
        ->orderByDesc('product_quantity')
        ->whereDate('order_date', Carbon::today())->get();

        $common = Product::query()->join('order_details', 'order_details.product_id', '=', 'products.id')
        ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
        ->select('order_details.product_id as product_id', 'orders.order_id as order_id', 
        'orders.order_date as order_date', 'products.name as product_name',
        'products.new_price as new_price')
        ->selectRaw('SUM(order_details.product_quantity) as product_quantity')
        ->groupBy('product_id', 'product_name', 'new_price', 'product_quantity', 'order_date', 'order_id')
        ->orderByDesc('product_quantity');

        $top_products_by_week = Product::join('order_details', 'order_details.product_id', '=', 'products.id')
        ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
        ->select('order_details.product_id as product_id', 'orders.order_id as order_id', 
        'orders.order_date as order_date', 'products.name as product_name',
        'products.new_price as new_price')
        ->selectRaw('SUM(order_details.product_quantity) as product_quantity')
        ->groupBy('product_id', 'product_name', 'new_price', 'product_quantity', 'order_date', 'order_id')
        ->orderByDesc('product_quantity')
        ->whereBetween('order_date', [now()->subDays(7)->toDateString(), now()->toDateString()])
        ->get();

        $top_products_by_month =Product::join('order_details', 'order_details.product_id', '=', 'products.id')
        ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
        ->select('order_details.product_id as product_id', 'orders.order_id as order_id', 
        'orders.order_date as order_date', 'products.name as product_name',
        'products.new_price as new_price')
        ->selectRaw('SUM(order_details.product_quantity) as product_quantity')
        ->groupBy('product_id', 'product_name', 'new_price', 'product_quantity', 'order_date', 'order_id')
        ->orderByDesc('product_quantity')
        ->whereBetween('order_date', [now()->subDays(30)->toDateString(), now()->toDateString()])
        ->get();

        $data = [
        'top_products_by_day' => handleData($top_products_by_day),
        'top_products_by_week' => handleData($top_products_by_week),
        'top_products_by_month' => handleData($top_products_by_month),
        ];

        return $data;
    }
}
