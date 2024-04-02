<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Shipping;
use App\Models\AtmPayment;
use App\Models\Product;

class UserOrderController
{
    
    public function submitReferralCode(Request $request){
    $referralCode = $request->referral_code;
    $existingUser = User::where('referral_code','=' , $referralCode)->first();
    if (!$existingUser) {
        return response()->json("Mã giới thiệu không tồn tại !", 404);
    }
    $usedReferralCodes = json_decode($existingUser->used_referral_codes, true) ?: [];
    if (in_array($referralCode, $usedReferralCodes)) {
        return response()->json("Bạn đã sử dụng mã này!", 404);
    }
    $usedReferralCodes[] = $referralCode;
    $newReferralCodes = json_encode($usedReferralCodes);
    $existingUser->update(['used_referral_codes' => $newReferralCodes]);

    return response()->json("Đã áp dụng mã giới thiệu của tài khoản: $existingUser->phone", 200);
}
    

    public function order(Request $request){
        $customMessages = [
            'required' => ':attribute là bắt buộc, không được để trống !',
            'regex' => ':attribute không hợp lệ.',
            'unique' => ':attribute đã tồn tại trong hệ thống.',
            'min' => ':attribute phải có ít nhất :min ký tự.',
        ];

        $customAttributes = [
            'shipping_name' => 'Họ và tên',
            'shipping_phone' => 'Số điện thoại',
            'shipping_address' => 'Địa chỉ giao hàng',
            'shipping_note' => 'Ghi chú đơn hàng'
        ];
        
        $validator = Validator::make($request->all(), [
            'shipping_name' => 'required|string',
            'shipping_phone' => ['required', 'regex:/^(\+84|0)[3|5|7|8|9][0-9]{8}$/'],
            'shipping_address' => 'required|string',
            'shipping_note' => 'required|string',
        ], $customMessages, $customAttributes);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Order
        $payment_method_id = $request->payment_method_id;
    
        if ($payment_method_id == 2) {
            $newAtmPayment = AtmPayment::create([
                'card_holder_name' => $request->card_holder_name,
                'card_number' => $request->card_number,
                'expiration_date' => $request->expiration_date,
                'security_code' => $request->security_code
            ]);
     
        } else {
            $newOrder = Order::create([
            'payment_method_id' => $payment_method_id,
            'order_total' => $request->order_total,
            'order_status' => 1,
            ]);
            $newOrderId = $newOrder->order_id;
    
            // Order Detail
            $carts = json_decode($request->carts, true);
    
            $orderDetails = [];
            foreach ($carts as $cart) {
                $orderDetails[] = [
                    'order_id' => $newOrderId,
                    'product_id' => $cart['id'],
                    'product_quantity' => $cart['quantity'],
                ];
            }
            OrderDetail::insert($orderDetails);
    
            // Shipping
            $newShipping = Shipping::create([
                'order_id' => $newOrderId,
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_note' => $request->shipping_note,
            ]);
    
                       
        }
    
        return ['success' => 'Đặt hàng thành công !'];
    }

    public function getOrders(Request $request){
        $phone = $request->phone;
        $order_details = OrderDetail::join('shippings', 'shippings.order_id', 'order_details.order_id')
        ->join('orders', 'orders.order_id', '=', 'order_details.order_id')
        ->join('products', 'products.id', 'order_details.product_id')
        ->select('order_detail_id as id', 'products.name as product_name', 'products.image as product_image', 'product_quantity', 'products.new_price', 
        'shippings.shipping_phone', 'orders.order_date', 'orders.order_status', 'orders.payment_method_id')
        ->where('shipping_phone', '=', $phone)
        ->selectRaw('sum(product_quantity * products.new_price) as product_total')
        ->groupBy('shipping_phone', 'order_detail_id', 'products.name', 'product_quantity', 'products.new_price', 'order_date', 'order_status', 
        'payment_method_id', 'product_image')
        ->get();

        $order_details->each(function ($order_detail) {
            $new_price = $order_detail->new_price;
            $product_total = (int) $order_detail->product_total;
            $order_detail->new_price = number_format($new_price, 0, ',', '.') . ' đ';
            $order_detail->product_total = number_format($product_total, 0, ',', '.') . ' đ';
            unset($product_total);
        });
           
        return response()->json($order_details, 200);
    }
    
}
