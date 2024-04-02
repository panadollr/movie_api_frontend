<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\User;


class UserAuthController
{
    public function register(Request $request){

        $customMessages = [
            'required' => ':attribute là bắt buộc, không được để trống !',
            'regex' => ':attribute không hợp lệ.',
            'unique' => ':attribute đã tồn tại trong hệ thống.',
        ];

        $customAttributes = [
            'name' => 'Tên người dùng',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu'
        ];
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => ['required', 'regex:/^(\+84|0)[3|5|7|8|9][0-9]{8}$/', 'unique:users'],
            'password' => 'required|string',
        ], $customMessages, $customAttributes);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        $referralCode = Str::random(8);
        while (User::where('referral_code', $referralCode)->exists()) {
            $referralCode = Str::random(8);
        }

        $name = $request->name;
        $phone = $request->phone;
        $password = $request->password;
        $newCustomer = User::create([
            'name' => $name,
            'phone' => $phone,
            'password' => md5($password),
            'referral_code' => $referralCode
        ]);
        
        if($newCustomer){
            return response()->json(['successful' => 'Đăng ký thành công !']);
        }
    }

    public function login(Request $request){
        $phone = $request->phone;
        $password = md5($request->password);
        $existingUser = User::where('phone', $phone)->first();
        if($existingUser){
            if($existingUser->password == $password){
                 return response()->json([
                    'successful' => 'Đăng nhập thành công !',
                    'user' => [
                        'id' => $existingUser->id,
                        'phone' => $phone,
                        'name' => $existingUser->name,
                        'referral_code' => $existingUser->referral_code,
                    ]
                ], 200); 
            } else {
                return response()->json(['error' => 'Mật khẩu không đúng !'], 404); 
            }
           
        } else {
            return response()->json(['error' => 'Tài khoản không tồn tại trong hệ thống !'], 500); 
        }
    }
}
