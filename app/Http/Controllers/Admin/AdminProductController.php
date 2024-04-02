<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\OrderDetail;

class AdminProductController
{

    public function getProducts(){
        $products = Product::all();
        $fakeProducts = [
            [
                "id" => 2,
                "name" => "Pizza DB taste",
                "description" => "ngon",
                "image" => "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Good_Food_Display_-_NCI_Visuals_Online.jpg/800px-Good_Food_Display_-_NCI_Visuals_Online.jpg",
                "old_price" => 0,
                "new_price" => 12000,
                "status" => 0,
                "category_id" => 2,
            ],
            [
                "id" => 3,
                "name" => "Pizza DB taste",
                "description" => "ngon",
                "image" => "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Good_Food_Display_-_NCI_Visuals_Online.jpg/800px-Good_Food_Display_-_NCI_Visuals_Online.jpg",
                "old_price" => 0,
                "new_price" => 12000,
                "status" => 0,
                "category_id" => 2,
            ],
            [
                "id" => 4,
                "name" => "Pizza DB taste",
                "description" => "ngon",
                "image" => "https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Good_Food_Display_-_NCI_Visuals_Online.jpg/800px-Good_Food_Display_-_NCI_Visuals_Online.jpg",
                "old_price" => 0,
                "new_price" => 12000,
                "status" => 0,
                "category_id" => 2,
            ],
        ];

        if(count($products) == 0) {
            sleep(1);
            Product::insert($fakeProducts);
        }

        $products = Product::all();

        if(count($products) > 0){
            $products->each(function ($product) {
                $product->key = $product->id;
            });

            return $products;
        } 

    }

    public function getProductDetails($id){
        $existingProduct = Product::where('id', '=', $id);
        if($existingProduct){
            $product = $existingProduct->first();
            $product->old_price = number_format($product->old_price, 0, ',', '.') . ' đ';
            $product->new_price = number_format($product->new_price, 0, ',', '.') . ' đ';
            return response()->json($product);
        } else {
            return response()->json(['message' => 'Sản phẩm không tồn tại !'], 404);
        }
        
    }
    
    public function addProduct(Request $request){
        $name = $request->name;
        $existingProduct = Product::where('name', $name)->first();
        if($existingProduct){
            return response()->json(['error' => 'Tên sản phẩm đã tồn tại !'], 404);
        }
        // $get_image= $request-> file('image');
        //      $new_image =rand(0,99).'.'.$get_image->getClientOriginalExtension();
        //      $get_image->move('product_images/',$new_image);

        $newProduct = Product::create([
            'name' => $name,
            'description' => $request->description,
            // 'image' => 'product_images/' . $new_image,
            'image' => $request->file,
            'old_price' => $request->old_price,
            'new_price' => $request->new_price,
            'status' => $request->status,
            'category_id' => $request->category_id
        ]);

        if($newProduct){
            return response()->json(['success' => 'Thêm sản phẩm thành công !'], 200);
        }else {
            return response()->json(['error' => 'Lỗi !'], 404);
        }
    }

    public function deleteProduct($product_id){
        $product = Product::find($product_id);
        $order_detail = OrderDetail::where('product_id', $product_id);
        
        if(!$product){
            return response()->json(['error' => "Sản phẩm không tồn tại !"], 200); 
        }
        
        $product->delete();
        if($order_detail){
            $order_detail->delete();
        }
       
        return response()->json(['success' => "Xóa sản phẩm thành công!"], 200);
    }

    public function updateProduct(Request $request){
        $id = $request->id;
        $name = $request->name; 
        if(Product::where('id', '!=', $id)->where('name', '=', $name)->exists()){
            return response()->json(['error' => 'Tên sản phẩm này đã tồn tại !'], 404);
        }

            Product::where('id', '=', $id)->update([
            'name' => $name,
            'description' => $request->description,
            'image' => $request->image,
            'old_price' => $request->old_price,
            'new_price' => $request->new_price,
            'status' => $request->status,
            'category_id' => $request->category_id
        ]);
        
            return response()->json(['success' => 'Cập nhật sản phẩm thành công !'], 200);
    }

    public function searchProducts(Request $request){
        $product_name = $request->name;
        $existingProducts = Product::select(['id', 'name', 'image', 'old_price'])
        ->where('name', 'LIKE', '%' . $product_name . '%')->get();
        
        if(count($existingProducts) > 0){
            $existingProducts->map(function ($product) {
                $product->old_price = number_format($product->old_price, 0, ',', '.') . ' đ';
                $product->new_price = number_format($product->new_price, 0, ',', '.') . ' đ';
                return $product;
            });
            return response()->json($existingProducts, 200);
        } else {
            return response()->json('Không tìm thấy sản phẩm !', 404);
        }
    }

   
}
