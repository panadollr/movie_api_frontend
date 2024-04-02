<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shipping;

class AdminOrderController
{
    public function getOrders(Request $request){
        $status = $request->status;
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 12;
        $query = Order::query()->orderBy('order_id','desc');

         if($status){
            $orders = $query->where('status', $status)->skip($offset)->take($limit)->get();;
        } else {
            $orders = $query->paginate($limit);
        }

        $orders = $orders->map(function ($order) {
            $order->id = $order->order_id;
            $order->key = $order->order_id;
            $order->order_total =  number_format($order->order_total, 0, ',', '.') . ' đ';
            unset($order->order_id);
            return $order;
        });
           
        return $orders;
    }

    public function updateOrderStatus(Request $request){
        $order_id = $request->order_id;
        $order_status = $request->order_status;
        if($order_status !== null){
            Order::where('order_id', $order_id)->update(['order_status' => $order_status]);
            return response()->json("Cập nhật tình trạng đơn hàng thành công!", 200);
        } else {
            return response()->json("Lỗi!", 404);
        }
    }

    public function getOrderDetails($order_id){
        $order_details = OrderDetail::where('order_id', '=', $order_id)
        ->join('products', 'products.id', 'order_details.product_id')
        ->select('order_detail_id as id', 'products.name', 'product_quantity', 'products.new_price')
        ->selectRaw('sum(product_quantity * products.new_price) as total')
        ->groupBy('order_detail_id', 'products.name', 'product_quantity', 'products.new_price')
        ->get();
        $order_details->each(function($order_detail){
            $order_detail->new_price = number_format($order_detail->new_price, 0, ',', '.') . ' đ'; 
            $order_detail->total = number_format($order_detail->total, 0, ',', '.') . ' đ'; 
        });
        $shipping = Shipping::where('order_id', '=', $order_id)->first();
        return [
            'order_details' => $order_details,
            'shipping' => $shipping
        ];
    }

}
