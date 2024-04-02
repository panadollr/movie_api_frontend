<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Category;

class UserCategoryController
{
    public function getCategories(){
        $categories = Category::all();

        $categories->each(function($category){
            $category->slug = Str::slug($category->name, '-');
        });
        return $categories;
    }
}
