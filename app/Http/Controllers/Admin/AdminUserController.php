<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController
{
    public function getUsers()
    {
        $users = User::select('id', 'name', 'phone', 'referral_code')->get();
        if (!$users) {
            return response()->json('Không có người dùng nào !', 404);
        }
        return response()->json($users, 200);
    }

    public function addUser(Request $request)
    {
        $customMessages = [
            'required' => ':attribute là bắt buộc, không được để trống !',
            'regex' => ':attribute không hợp lệ.',
            'unique' => ':attribute đã tồn tại trong hệ thống.',
            'min' => ':attribute phải có ít nhất :min ký tự.',
        ];

        $customAttributes = [
            'name' => 'Tên người dùng',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu'
        ];
        
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => ['required', 'regex:/^(\+84|0)[3|5|7|8|9][0-9]{8}$/', 'unique:users'],
            'password' => 'required|string|min:8',
        ], $customMessages, $customAttributes);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }
        
        $referralCode = \Str::random(8);
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
            return response()->json('Tạo tài khoản thành công !', 200);
        }
    }

    public function deleteUser($id)
    {
        $user = User::where('id', $id);
        if (!$user) {
            return response()->json('Không tìm thấy người dùng !', 404);
        }
        $user->delete();
        return response()->json('Xóa người dùng thành công !', 200);
    }
}
