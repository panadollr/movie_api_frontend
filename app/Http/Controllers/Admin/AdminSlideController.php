<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Models\Slide;

class AdminSlideController 
{
    public function getSlides(){
    $slides = Slide::all();
    return response()->json($slides, 200);
    }

    public function addSlide($image){
        $result = Slide::create(['image' => $image]);
        if(!$result){
            return response()->json("Lỗi !", 404);
        }
        return response()->json("Thêm slide thành công !", 200);
    }

    public function updateSlide($id, $image){
        $result = Slide::where('id', $id)->update(['image' => $image]);
        if(!$result){
            return response()->json("Lỗi !", 404);
        }
        return response()->json("Cập nhật slide thành công !", 200);
    }

}
