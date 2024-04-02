<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Category;

class UserProductController
{
    
    public function getProducts(Request $request){
        try {
            $category_id = $request->category_id;
            $type = $request->type;
            $offset = $request->offset ?? 0;
            $limit = $request->limit ?? 12;
    
            $query = Product::query();
            if ($category_id && Category::find($category_id)) {
                $products = $query->where('category_id', $category_id)->paginate($limit);
            } else if($type == "moi-nhat"){
                $products = $query->orderBy('id','desc')->skip($offset)->take($limit)->get();;
            } else if($type == "ban-chay"){
                $products = $query->join('order_details', 'order_details.product_id', '=', 'products.id')
        ->groupBy('products.id', 'products.name', 'products.image', 'products.old_price', 'products.new_price', 'products.description')
        ->orderByDesc('product_quantity')
        ->select([
            'products.id',
            'products.name',
            'products.image',
            'products.old_price',
            'products.new_price',
            'products.description',
            \DB::raw('SUM(order_details.product_quantity) as product_quantity')
        ])
        ->skip($offset)
        ->take($limit)
        ->get();
            } else {
                $products = $query->paginate($limit);
            }
    
            $newProducts = [];
            $products->each(function ($product) {
                $old_price = $product->old_price;
                $new_price = $product->new_price;
                $product->percent_discount = strval(round((($old_price - $new_price) / $old_price) * 100)) . "%";
            });
    
            if($type == "sieu-giam-gia") {
                $products = $products->sortByDesc('percent_discount')->values();
            }
               
            return $products;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
       
    }
    

    public function getProductsByCategorySlug($category_slug){
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 12;

        $categories = Category::all();
        $matchingCategory = $categories->first(function($category) use($category_slug) {
            $category_name = Str::slug($category->name, '-');
            return $category_name == $category_slug;
        });
        

        if ($matchingCategory) {
            $products = Product::select('id', 'name', 'image', 'old_price', 'new_price', 'category_id')
            ->where('category_id', '=', $matchingCategory->id)->skip($offset)->take($limit)->get();;
            $products->each(function ($product) {
                $old_price = $product->old_price;
                $new_price = $product->new_price;
                $product->percent_discount = strval(round((($old_price - $new_price) / $old_price) * 100)) . "%";
                $product->old_price = number_format($old_price, 0, ',', '.') . ' đ';
                $product->new_price = number_format($new_price, 0, ',', '.') . ' đ';
            });
            if(count($products) < 0){
               return response()->json(['message' => 'Không có sản phẩm của danh mục này !'], 404);
            }else {
                return response()->json($products, 200);
            }
        } else {
            return response()->json(['message' => 'Danh mục không tồn tại !'], 404);
        }
    }


    public function getProductDetails($id)
{
    $product = Product::find($id);

    if ($product) {
        return response()->json($product, 200);
    } else {
        return response()->json(['message' => 'Sản phẩm không tồn tại!'], 404);
    }
}

    public function searchProducts(Request $request){
        $product_name = $request->product_name;
        $existingProducts = Product::select(['id', 'name', 'image', 'new_price', 'old_price'])
        ->where('name', 'LIKE', '%' . $product_name . '%')->get();
        
        if(count($existingProducts) > 0){
            $existingProducts->map(function ($product) {
                $product->new_price = number_format($product->new_price, 0, ',', '.') . ' đ';
                $product->old_price = number_format($product->old_price, 0, ',', '.') . ' đ';
                return $product;
            });
            return response()->json($existingProducts, 200);
        } else {
            return response()->json(['error' => 'Không tìm thấy sản phẩm !'], 404);
        }
    }


    
    public function getSimiliarProducts($id){
        $category_id = Product::find($id)->category_id;
        $products = Product::where('id', '!=', $id)
                   ->where('category_id', '=', $category_id)
                   ->get();

        $products->each(function($product){
            $old_price = $product->old_price;
            $new_price = $product->new_price;
            $product->percent_discount = strval(round((($old_price - $new_price) / $old_price) * 100)) . "%";
            $product->old_price = number_format($old_price, 0, ',', '.') . ' đ';
            $product->new_price = number_format($new_price, 0, ',', '.') . ' đ';
        });

        return $products;
    }
  

   
}
