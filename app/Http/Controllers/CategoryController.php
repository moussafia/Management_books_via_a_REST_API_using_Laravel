<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
           'category' => 'required|string|max:255|unique:categories'
        ]);
        $category=new Category;
        $category->create($request->all());
        return response()->json([
            "message"=>"category is succesfuly created",
        ],201);
    }

  
    public function update(Request $request,$id)
    {
        $request->validate([
            'category' => 'required|string|max:255|unique:categories'
         ]);
         $category=Category::findOrFail($id);
         $category->update($request->all());
         return response()->json([
             "message"=>"category is succesfuly updated",
             "category"=>$category
         ],201);
    }

  
    public function destroy($id)
    {
        $category=new Category;
        $category->destroy($id);
        return response()->json([
            "message"=> "data is succesfuly deleted",
        ],200);
    }
}
